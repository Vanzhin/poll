<?php

namespace App\Controller\Admin;

use App\Entity\Section;
use App\Form\SectionFormType;
use App\Repository\SectionRepository;
use App\Service\FormService;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SectionController extends AbstractController
{
    #[Route('/admin/section', name: 'app_admin_section')]
    public function index(Paginator $paginator, SectionRepository $repository): Response
    {
        return $this->render('admin/section/index.html.twig',
            [
                'pagination' => $paginator->getPagination($repository->findLatestQuery(), 5)
            ]

        );
    }

    #[Route("admin/section/create", name: 'app_admin_section_create')]
    public function create(Request $request, EntityManagerInterface $em, FormService $formService): Response
    {
        $form = $this->createForm(SectionFormType::class, new Section());
        if ($formService->handle($form, $request, $em)) {
            $this->addFlash('section_flash', 'Область аттестации создана');
            return $this->redirectToRoute('app_admin_section');
        }

        return $this->render('admin/section/create.html.twig', [
            'sectionForm' => $form->createView(),
            'buttonText' => 'Создать',
            'titleText' => 'Создание области аттестации'
        ]);
    }


    #[Route("admin/section/{id}/edit", name: 'app_admin_section_edit')]
    public function edit(Section $section, Request $request, EntityManagerInterface $em, FormService $formService): Response
    {
        $form = $this->createForm(SectionFormType::class, $section);
        if ($formService->handle($form, $request, $em)) {
            $this->addFlash('section_flash', 'Область аттестации обновлена.');
            return $this->redirectToRoute('app_admin_section');
        }

        return $this->render('admin/section/create.html.twig', [
            'sectionForm' => $form->createView(),
            'buttonText' => 'Обновить',
            'titleText' => 'Обновление области аттестации'
        ]);
    }

    #[Route("admin/section/{id}/delete", name: 'app_admin_section_delete')]
    public function delete(Section $section, EntityManagerInterface $em): Response
    {

        $em->remove($section);
        $em->flush();
        $this->addFlash('section_flash', 'Область аттестации удалена.');
        return $this->redirectToRoute('app_admin_section');

    }
}
