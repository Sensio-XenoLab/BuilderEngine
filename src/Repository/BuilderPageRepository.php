<?php

namespace VeeZions\BuilderEngine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use VeeZions\BuilderEngine\Entity\BuilderPage;
use VeeZions\BuilderEngine\Trait\SearchTrait;

/**
 * @extends ServiceEntityRepository<BuilderPage>
 */
class BuilderPageRepository extends ServiceEntityRepository
{
    use SearchTrait;

    public function __construct(
        ManagerRegistry $registry,
        private readonly PaginatorInterface $paginator,
    ) {
        parent::__construct($registry, BuilderPage::class);
    }

    /*
     * @return array<string, mixed>|null
     */
    public function getPage(string $route, string $locale): ?array
    {
        $locale = in_array(strtolower($locale), $this->availableTranslations, true) ? $locale : 'En';
        $page = $this->createQueryBuilder('p')
            ->select(
                'p.id',
                "p.title{$locale} as title",
                'p.content as content',
                'p.seo',
                'p.route',
                'p.createdAt',
                'p.updatedAt',
            )
            ->where('p.route = :route')
            ->setParameter('route', $route)
            ->getQuery()
            ->getOneOrNullResult();

        return is_array($page) ? $page : null;
    }

    /*
     * @param array<string, string>                  $search
     * @param array<int, string>                     $columns
     * @param array<int, array<string, string|null>> $searchKeys
     *
     * @return PaginationInterface<int, mixed>
     */
    public function paginate(
        int $page,
        array $search,
        string $order,
        array $columns,
        array $searchKeys,
    ): PaginationInterface {
        $query = $this->createQueryBuilder('p')->select($columns);
        $query = $this->scopeSearch($query, $search, $searchKeys);

        return $this->paginator->paginate($query->orderBy('p.createdAt', $order), $page, 10);
    }
}
