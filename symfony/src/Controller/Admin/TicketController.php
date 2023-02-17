<?php

namespace App\Controller\Admin;

use App\Entity\Test;
use App\Entity\Ticket;
use App\Form\TestFormType;
use App\Form\TicketFormType;
use App\Repository\TicketRepository;
use App\Service\FormService;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    #[Route("admin/ticket", name: 'app_admin_ticket')]
    public function index(Paginator $paginator, TicketRepository $repository): Response
    {
        return $this->render('admin/ticket/index.html.twig',
            [
                'pagination' => $paginator->getPagination($repository->findLastUpdatedQuery(), 10)
            ]

        );
    }

    //todo доделать - продумать как выводить вопросы и тесты
    #[Route("admin/ticket/create", name: 'app_admin_ticket_create')]
    public function create(Request $request, EntityManagerInterface $em, FormService $formService): Response
    {
        $form = $this->createForm(TicketFormType::class, new Ticket());
        if ($formService->handle($form, $request, $em)) {
            $this->addFlash('ticket_flash', 'Билет создан');
            return $this->redirectToRoute('app_admin_ticket');
        }

        return $this->render('admin/ticket/create.html.twig', [
            'ticketForm' => $form->createView(),
            'buttonText' => 'Создать',
            'titleText' => 'Создание билета',
            'create' => true,

        ]);
    }


    #[Route("admin/ticket/{id}/edit", name: 'app_admin_ticket_edit')]
    public function edit(Ticket $ticket, Request $request, EntityManagerInterface $em, FormService $formService): Response
    {
        $form = $this->createForm(TicketFormType::class, $ticket);
        if ($formService->handle($form, $request, $em)) {
            $this->addFlash('ticket_flash', 'Билет обновлен');
            return $this->redirectToRoute('app_admin_ticket');
        }

        return $this->render('admin/ticket/create.html.twig', [
            'ticketForm' => $form->createView(),
            'buttonText' => 'Обновить',
            'titleText' => 'Обновление билета',
            'create' => false,

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
