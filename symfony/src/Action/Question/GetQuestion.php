<?php

namespace App\Action\Question;

use App\Entity\Question;
use App\Handler\QuestionHandler;
use App\Response\Question\ErrorResponse;
use App\Response\Question\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetQuestion
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ErrorResponse          $errorResponse,
        private readonly SuccessResponse        $successResponse,
        private readonly QuestionHandler        $questionHandler,
    )
    {
    }

    public function get(Request $request): JsonResponse
    {
        try {
            $questionId = $request->attributes->get('_route_params', [])['id'];
            $question = $this->em->find(Question::class, $questionId);

            if ($question) {
                return $this->successResponse->response(['content' => $this->questionHandler->get($question,'json', ['groups' => 'admin_question'])]);

            } else {
                throw new \Exception(sprintf('Вопрос с идентификатором %s не обнаружен', $questionId));
            }

        } catch (\Exception $e) {
            return $this->errorResponse->response(['error' => $e->getMessage()]);
        }

    }

}