<?php

namespace XenoLabs\XenoEngine\Manager;

use XenoLabs\XenoEngine\Constant\AssetManagerConstant;
use XenoLabs\XenoEngine\Enum\GedTypeEnum;
use XenoLabs\XenoEngine\Entity\Ged;
use XenoLabs\XenoEngine\Repository\GedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\InvalidStreamProvided;
use League\Flysystem\UnableToWriteFile;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class AssetManager
{
    public function __construct(
        private FilesystemOperator $uploadsFilesystem,
        private CacheManager $cacheManager,
        private ParameterBagInterface $params,
        private AssetManagerConstant $assetManagerConstant,
        private FilterManager $filterManager,
        private DataManager $dataManager,
        private EntityManagerInterface $entityManager,
        private GedRepository $gedRepository,
    ) {
    }

    /**
     * @param array<string> $filters
     *
     * @throws FilesystemException
     */
    public function upload(mixed $uploadedFile, array $filters = [], string $context = 'ged', mixed $slug = null): ?string
    {
        try {
            if ($uploadedFile instanceof UploadedFile) {
                $type = match ($uploadedFile->getClientMimeType()) {
                    'image/jpg',
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                    'video/mp4',
                    'video/mpeg',
                    'video/quicktime',
                    'video/x-msvideo',
                    'video/x-ms-wmv' => AssetManagerConstant::MEDIA,
                    default => AssetManagerConstant::DOCUMENT,
                };

                $type = match ($context) {
                    'chat' => AssetManagerConstant::CHAT.($slug ? $slug.'/' : ''),
                    'account' => AssetManagerConstant::ACCOUNT.'/'.$slug.'/',
                    default => $type,
                };

                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $type.Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $getID3 = new \getID3();
                $fileData = $getID3->analyze($uploadedFile->getPathname());
                $fileInfo = [
                    'size' => $fileData['filesize'],
                    'width' => isset($fileData['video']) ? $fileData['video']['resolution_x'] : null,
                    'height' => isset($fileData['video']) ? $fileData['video']['resolution_y'] : null,
                ];

                $stream = fopen($uploadedFile->getPathname(), 'r');
                $this->uploadsFilesystem->writeStream($newFilename, $stream);
                $this->setPermissions();

                if (is_resource($stream)) {
                    fclose($stream);
                }

                if ('ged' === $context) {
                    if (in_array($uploadedFile->getMimeType(), ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'], true)) {
                        foreach ($filters as $filter) {
                            $this->storeVariant($filter, $newFilename);
                        }
                    }
                    $this->recordNewFileInGed($newFilename, $uploadedFile, $fileInfo);
                }

                return $newFilename;
            }

            return null;
        } catch (FilesystemException|UnableToWriteFile $e) {
            throw new InvalidStreamProvided($e->getMessage());
        }
    }

    private function setPermissions(): void
    {
        $env = $this->params->get('kernel.environment');
        if (in_array($env, ['dev', 'test'], true)) {
            $projectDir = $this->params->get('kernel.project_dir');
            $filesystem = new Filesystem();
            $uploadsDir = is_string($projectDir) ? $projectDir.'/public/uploads' : null;

            try {
                if (null !== $uploadsDir && $filesystem->exists($uploadsDir)) {
                    $filesystem->chmod($uploadsDir, 0755, 0000, true);
                }
            } catch (IOException $e) {
            }
        }
    }

    /**
     * @param array<string, int|null> $fileInfo
     */
    private function recordNewFileInGed(string $newFilename, UploadedFile $uploadedFile, array $fileInfo): void
    {
        $ged = new Ged();
        $ged->setUrl($newFilename)
            ->setTitle(str_replace(
                '.'.$uploadedFile->getClientOriginalExtension(),
                '',
                $uploadedFile->getClientOriginalName())
            )
            ->setSize($fileInfo['size'])
            ->setWidth($fileInfo['width'])
            ->setHeight($fileInfo['height'])
            ->setType($this->getTypeFromMime($uploadedFile))
            ->setMime($uploadedFile->getClientMimeType());
        $this->entityManager->persist($ged);
        $this->entityManager->flush();
    }

    private function deleteMedia(string $oldFileName): void
    {
        $oldFileName = str_starts_with($oldFileName, '/')
            ? substr($oldFileName, 1)
            : $oldFileName;
        $ged = $this->gedRepository->findOneBy(['url' => $oldFileName]);
        if (null !== $ged) {
            $pages = $ged->getPage();
            foreach ($pages as $page) {
                $ged->removePage($page);
            }
            $articles = $ged->getArticle();
            foreach ($articles as $article) {
                $ged->removeArticle($article);
            }
            $categories = $ged->getCategory();
            foreach ($categories as $category) {
                $ged->removeCategory($category);
            }
            $this->entityManager->remove($ged);
            $this->entityManager->flush();
        }
    }

    public function replace(?string $oldFileName, ?UploadedFile $uploadedFile, string $context = 'ged', mixed $slug = null): ?string
    {
        try {
            $this->delete($oldFileName, $context);

            return $this->upload($uploadedFile, [], $context, $slug);
        } catch (FilesystemException $e) {
            throw new InvalidStreamProvided($e->getMessage());
        }
    }

    /**
     * @throws FilesystemException
     */
    public function delete(?string $oldFileName, string $context = 'ged'): void
    {
        try {
            if ($oldFileName) {
                $env = $this->params->get('kernel.environment');
                if (in_array($env, ['dev', 'test'], true)) {
                    $oldFileName = '/'.$oldFileName;
                }
                $this->uploadsFilesystem->delete($oldFileName);
                if ('ged' === $context) {
                    $this->cacheManager->remove($oldFileName);
                    $this->deleteMedia($oldFileName);
                }
            }
        } catch (FilesystemException $e) {
            throw new InvalidStreamProvided($e->getMessage());
        }
    }

    private function storeVariant(string $filter, string $fileName): void
    {
        if (in_array($filter, $this->assetManagerConstant->getFilters(), true)
            && !$this->cacheManager->isStored($fileName, $filter)
        ) {
            $binary = $this->dataManager->find($filter, $fileName);
            $filteredBinary = $this->filterManager->applyFilter($binary, $filter);
            $this->cacheManager->store($filteredBinary, $fileName, $filter);
        }
    }

    public function getUrlFromAwsS3(?string $value): ?string
    {
        if (null === $value) {
            return null;
        }

        $env = $this->params->get('kernel.environment');
        if (in_array($env, ['dev', 'test'], true)) {
            return str_starts_with($value, '/') ? $value : '/'.$value;
        }

        $awsURL = $this->params->get('uploads_base_url');

        return 'string' === gettype($awsURL) ? $awsURL.$value : null;
    }

    public function getUrlFromCdn(?string $value): ?string
    {
        if (null === $value) {
            return null;
        }

        $env = $this->params->get('kernel.environment');
        if (in_array($env, ['dev', 'test'], true)) {
            return str_starts_with($value, '/') ? $value : '/'.$value;
        }

        $cloudFrontUrl = $this->params->get('cdn_base_url');

        return 'string' === gettype($cloudFrontUrl) ? $cloudFrontUrl.$value : null;
    }

    public function getTypeFromMime(UploadedFile $uploadedFile): GedTypeEnum
    {
        return match ($uploadedFile->getMimeType()) {
            'image/jpeg',
            'image/png',
            'image/gif' => GedTypeEnum::IMAGE,
            'application/pdf',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/csv' => GedTypeEnum::DOCUMENT,
            'video/mp4',
            'video/mpeg',
            'video/quicktime',
            'video/x-msvideo',
            'video/x-ms-wmv' => GedTypeEnum::VIDEO,
            default => GedTypeEnum::UNKNOWN,
        };
    }

    /**
     * @throws FilesystemException
     */
    public function deleteRemoteFolderIfEmpty(string $folder): void
    {
        $this->uploadsFilesystem->directoryExists($folder);
        $files = $this->uploadsFilesystem->listContents($folder);
        if (empty($files->toArray())) {
            $this->uploadsFilesystem->deleteDirectory($folder);
        }
    }
}
