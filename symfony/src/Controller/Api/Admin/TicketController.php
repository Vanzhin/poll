<?php

namespace App\Controller\Api\Admin;

use App\Entity\Test;
use App\Entity\Ticket;
use App\Factory\Ticket\TicketFactory;
use App\Repository\TicketRepository;
use App\Service\TicketService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class TicketController extends AbstractController
{

    #[Route('/api/admin/ticket/{id}', name: 'app_api_admin_test_show', methods: 'GET')]
    public function show(Ticket $ticket): JsonResponse
    {
        return $this->json(
            $ticket,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin_ticket',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/ticket/create', name: 'app_api_admin_ticket_create', methods: 'POST')]
    public function create(Request $request, TicketService $ticketService, TicketFactory $factory): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $ticket = $factory->createBuilder()->buildTicket($data);
        $response = $ticketService->saveIfValid($ticket);
        return $this->json($response['response'],
            $response['status'],
            ['charset=utf-8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/api/admin/ticket/test/{id}/get_last_title', name: 'app_api_admin_ticket_get_last_title', methods: 'GET')]
    public function getLastTitleByTest(Test $test, TicketRepository $repository): JsonResponse
    {
        $ticket = $repository->findLastTitleByTest($test);

        return $this->json($ticket,
            200,
            ['charset=utf-8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/ticket/{id}/edit', name: 'app_api_admin_ticket_edit', methods: 'POST')]
    public function edit(Ticket $ticket, Request $request, TicketService $ticketService, TicketFactory $factory): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ticket = $factory->createBuilder()->buildTicket($data, $ticket);
        $response = $ticketService->saveIfValid($ticket);
        return $this->json($response['response'],
            $response['status'],
            ['charset=utf-8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/ticket/{id}/delete', name: 'app_api_admin_ticket_delete', methods: 'GET')]
    public function delete(Ticket $ticket, TicketService $ticketService): JsonResponse
    {
        $response = $ticketService->deleteResponse($ticket);
        return $this->json($response['response'],
            $response['status'],
            ['charset=utf-8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
