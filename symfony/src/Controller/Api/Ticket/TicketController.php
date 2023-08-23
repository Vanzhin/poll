<?php

namespace App\Controller\Api\Ticket;

use App\Controller\Api\Ticket\Action\BreadcrumbsAction;
use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/ticket', name: 'app_api_ticket_')]
class TicketController extends AbstractController
{

    public function __construct(
        private readonly BreadcrumbsAction $breadcrumbsAction
    )
    {
    }

    #[Route('/{id}/breadcrumbs', name: 'breadcrumbs', methods: ['GET'])]
    public function getTicketQuestion(Ticket $ticket): JsonResponse
    {
        return $this->breadcrumbsAction->run($ticket);
    }
}
