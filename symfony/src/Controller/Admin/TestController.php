<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Section;
use App\Entity\Test;
use App\Form\TestFormType;
use App\Repository\TestRepository;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class TestController extends AbstractController
{
    #[Route('/admin/test', name: 'app_admin_test')]
    public function index(Paginator $paginator, TestRepository $repository): Response
    {
        return $this->render('admin/test/index.html.twig',
            [
                'pagination' => $paginator->getPagination($repository->findLatsUpdatedQuery(), 10)
            ]

        );
    }

    #[Route("admin/test/create", name: 'app_admin_test_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TestFormType::class, new Test());
        if ($this->formHandle($form, $request, $em)) {
            $this->addFlash('test_flash', 'Тест создан');
            return $this->redirectToRoute('test');
        }

        return $this->render('admin/test/create.html.twig', [
            'testForm' => $form->createView(),
            'buttonText' => 'Создать',
            'titleText' => 'Создание теста'
        ]);
    }


    #[Route("admin/test/{id}/edit", name: 'app_admin_test_edit')]
    public function edit(Test $test, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TestFormType::class, $test);
        if ($this->formHandle($form, $request, $em)) {
            $this->addFlash('test_flash', 'Тест обновлен');
            return $this->redirectToRoute('app_admin_test');
        }

        return $this->render('admin/test/create.html.twig', [
            'testForm' => $form->createView(),
            'buttonText' => 'Обновить',
            'titleText' => 'Обновление теста'
        ]);
    }

    #[Route("admin/test/{id}/delete", name: 'app_admin_test_delete')]
    public function delete(Test $test, EntityManagerInterface $em): Response
    {

        $em->remove($test);
        $em->flush();
        $this->addFlash('test_flash', 'Тест удален');
        return $this->redirectToRoute('app_admin_test');

    }

    private function formHandle(FormInterface $form, Request $request, EntityManagerInterface $em): ?FormInterface
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Article $article
             */
            $article = $form->getData();

            $em->persist($article);
            $em->flush();
            return $form;

        }
        return null;
    }
}
