<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use App\Service\FormService;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/admin/article', name: 'app_admin_article')]
    public function index(Paginator $paginator, ArticleRepository $repository): Response
    {
        return $this->render('admin/article/index.html.twig',
            [
                'pagination' => $paginator->getPagination($repository->findLastUpdatedQuery(), 5)
            ]

        );
    }
    #[Route("admin/article/create", name: 'app_admin_article_create')]
    public function create(Request $request, EntityManagerInterface $em, FormService $formService): Response
    {
        $form = $this->createForm(ArticleFormType::class, new Article());
        if ($formService->handle($form, $request, $em)) {
            $this->addFlash('article_flash', 'Раздел создан');
            return $this->redirectToRoute('app_admin_article');
        }

        return $this->render('admin/article/create.html.twig', [
            'articleForm' => $form->createView(),
            'buttonText' => 'Создать',
            'titleText' => 'Создание раздела'
        ]);
    }


    #[Route("admin/article/{id}/edit", name: 'app_admin_article_edit')]
    public function edit(Article $article, Request $request, EntityManagerInterface $em, FormService $formService): Response
    {
        $form = $this->createForm(ArticleFormType::class, $article);
        if ($formService->handle($form, $request, $em)) {
            $this->addFlash('article_flash', 'Раздел обновлен.');
            return $this->redirectToRoute('app_admin_article');
        }

        return $this->render('admin/article/create.html.twig', [
            'articleForm' => $form->createView(),
            'buttonText' => 'Обновить',
            'titleText' => 'Обновление раздела'
        ]);
    }
    #[Route("admin/article/{id}/delete", name: 'app_admin_article_delete')]
    public function delete(Article $article, EntityManagerInterface $em): Response
    {

        $em->remove($article);
        $em->flush();
        $this->addFlash('article_flash', 'Раздел удален.');
        return $this->redirectToRoute('app_admin_article');

    }
}
