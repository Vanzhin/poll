<?php

namespace App\Controller\Api;

use App\Entity\Ticket;
use App\Service\QuestionHandler;
use App\Service\SessionService;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
//    #[Route('/api/ticket/{id}/question', name: 'app_api_ticket_question', methods: ['GET'])]
//    public function getTicketQuestion(Ticket $ticket, QuestionHandler $questionService, SessionService $sessionService): JsonResponse
//    {
//
//        try {
//            $sessionService->remove(QuestionHandler::SHUFFLED);
//            $questions = $questionService->getPreparedQuestions($ticket->getQuestion()->toArray());
//            $response = [
//                'test' => $ticket->getTest()->getId(),
//                'questions' => $questions
//            ];
//            $sessionService->add($questionService->prepareForSession($questions), QuestionHandler::SHUFFLED);
//
//            $status = 200;
//        } catch (Exception $e) {
//            $response = ['error' => $e->getMessage()];
//            $status = 422;
//        } finally {
//            return $this->json($response,
//                $status,
//                ['charset=utf-8'],
//                ['groups' => 'main'],
//            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
//        }
//    }
}
