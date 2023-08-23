<?php

namespace App\Action\Question;

use App\Action\BaseAction;
use App\Entity\Question;
use App\Repository\Question\QuestionRepository;
use App\Service\Paginator;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetQuestion extends BaseAction
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Paginator              $paginator,
        private readonly QuestionRepository     $repository,
        private readonly SerializerService      $serializer
    )
    {
        parent::__construct($serializer);

    }

    public function get(Request $request): JsonResponse
    {
        try {
            $questionId = $request->attributes->get('_route_params', [])['id'];
            $question = $this->em->find(Question::class, $questionId);

            if ($question) {
                return $this->successResponse($question, ['groups' => 'admin_question']);

            } else {
                throw new \Exception(sprintf('Вопрос с идентификатором %s не обнаружен', $questionId));
            }

        } catch (\Exception $e) {
            return $this->errorResponse(['error' => $e->getMessage()]);
        }

    }

    public function getAll(): JsonResponse
    {

        try {
            $pagination = $this->paginator->getPagination($this->repository->findLastUpdatedQuery());
            if ($pagination->count() > 0) {
                return $this->successResponse(
                    [
                        "question" => $pagination,
                        "pagination" => $this->paginator->getInfo($pagination)
                    ],
                    ['groups' => 'admin_question']);


            }
            return $this->successResponse(["pagination" => $this->paginator->getInfo($pagination)]);

        } catch (\Exception $e) {
            return $this->errorResponse(['error' => $e->getMessage()]);
        }
    }
}