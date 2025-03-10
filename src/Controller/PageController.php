<?php

namespace VeeZions\BuilderEngine\Controller;

use VeeZions\BuilderEngine\Constant\Crud\PageConstant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment as TwigEnvironment;
use VeeZions\BuilderEngine\Entity\BuilderPage;
use VeeZions\BuilderEngine\Form\PageType;
use VeeZions\BuilderEngine\Manager\FormManager;
use VeeZions\BuilderEngine\Trait\AccessTrait;
use VeeZions\BuilderEngine\Trait\PaginationTrait;

class PageController
{
    use AccessTrait;
    use PaginationTrait;

    public function __construct(
        private TwigEnvironment $twig,
        private Router $router,
        private TranslatorInterface $translator,
        private EntityManagerInterface $entityManager,
        private AuthorizationCheckerInterface $authorizationChecker,
        private FormManager $formManager,
        private PageConstant $constant,
        private array $actions,
    ) {
    }

    public function index(Request $request): Response
    {
        $data = $this->getPaginationData(
            $request,
            BuilderPage::class,
            $this->constant->getCrudConfig(),
            $this->entityManager
        );

        return new Response($this->twig->render('@BuilderEngineBundle/pages/index.html.twig', [
            'title' => $this->formManager->translateCrudTitle('pages', 'index'),
            'data' => $data,
        ]));
    }

    public function new(Request $request): Response
    {
        $this->isGranted($this->actions['new']['roles']);
        $form = $this->formManager->form(PageType::class);

        return new Response($this->twig->render('@BuilderEngineBundle/pages/new-edit.html.twig', [
            'title' => $this->formManager->translateCrudTitle('page', 'new'),
            'form' => $form
        ]));
    }

    public function show(?BuilderPage $page): Response
    {
        $this->isGranted($this->actions['show']['roles']);

        if (null === $page) {
            throw new NotFoundHttpException($this->translator->trans('error.page.not.found', [], 'BuilderEngineBundle-errors'));
        }

        return new Response($this->twig->render('@BuilderEngineBundle/pages/show.html.twig', [
            'title' => $this->formManager->translateCrudTitle('page', 'show')
        ]));
    }

    public function edit(?BuilderPage $page, Request $request): Response
    {
        $this->isGranted($this->actions['edit']['roles']);

        if (null === $page) {
            throw new NotFoundHttpException($this->translator->trans('error.page.not.found', [], 'BuilderEngineBundle-errors'));
        }
        $form = $this->formManager->form(PageType::class, $page);

        return new Response($this->twig->render('@BuilderEngineBundle/pages/new-edit.html.twig', [
            'title' => $this->formManager->translateCrudTitle('page', 'edit'),
            'form' => $form
        ]));
    }

    public function delete(?BuilderPage $page): Response
    {
        $this->isGranted($this->actions['delete']['roles']);

        if (null !== $page) {
            $this->entityManager->remove($page);
        }

        return new RedirectResponse($this->router->generate('xlxeb_controller_page_index'));
    }
}
