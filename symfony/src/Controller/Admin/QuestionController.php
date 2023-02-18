<?php

namespace App\Controller\Admin;

use App\Entity\Ticket;
use App\Form\TicketFormType;
use App\Repository\QuestionRepository;
use App\Service\FormService;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/admin/question', name: 'app_admin_question')]
    public function index(Paginator $paginator, QuestionRepository $repository): Response
    {
        return $this->render('admin/question/index.html.twig',
            [
                'pagination' => $paginator->getPagination($repository->findLastUpdatedQuery(), 10)
            ]

        );
    }

    #[Route("admin/question/create", name: 'app_admin_question_create')]
    public function create(Request $request, EntityManagerInterface $em, FormService $formService): Response
    {
        $form = $this->createForm(TicketFormType::class, new Ticket());
        if ($formService->handle($form, $request, $em)) {
            $this->addFlash('question_flash', 'Вопрос создан');
            return $this->redirectToRoute('app_admin_question');
        }

        return $this->render('admin/question/create.html.twig', [
            'questionForm' => $form->createView(),
            'buttonText' => 'Создать',
            'titleText' => 'Создание вопроса',
//            'create' => true,

        ]);
    }


    #[Route("admin/question/{id}/edit", name: 'app_admin_question_edit')]
    public function edit(Ticket $ticket, Request $request, EntityManagerInterface $em, FormService $formService): Response
    {
        $form = $this->createForm(TicketFormType::class, $ticket);
        if ($formService->handle($form, $request, $em)) {
            $this->addFlash('ticket_flash', 'Билет обновлен');
            return $this->redirectToRoute('app_admin_question');
        }

        return $this->render('admin/question/create.html.twig', [
            'questionForm' => $form->createView(),
            'buttonText' => 'Обновить',
            'titleText' => 'Обновление вопроса',
//            'create' => false,

        ]);
    }

    #[Route("admin/ticket/{id}/delete", name: 'app_admin_ticket_delete')]
    public function delete(Ticket $ticket, EntityManagerInterface $em): Response
    {

        $em->remove($ticket);
        $em->flush();
        $this->addFlash('ticket_flash', 'Билет удален');
        return $this->redirectToRoute('app_admin_ticket');

    }
}
