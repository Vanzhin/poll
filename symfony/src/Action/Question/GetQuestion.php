<?php

namespace App\Action\Question;

use App\Entity\Question;
use App\Handler\QuestionHandler;
use App\Repository\QuestionRepository;
use App\Response\Question\ErrorResponse;
use App\Response\Question\SuccessResponse;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class GetQuestion
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ErrorResponse          $errorResponse,
        private readonly SuccessResponse        $successResponse,
        private readonly QuestionHandler        $questionHandler,
        private readonly Paginator              $paginator,
        private readonly QuestionRepository     $repository
    )
    {
    }

    public function get(Request $request): JsonResponse
    {
        try {
            $questionId = $request->attributes->get('_route_params', [])['id'];
            $question = $this->em->find(Question::class, $questionId);

            if ($question) {
                return $this->successResponse->response(['content' => $this->questionHandler->get($question, 'json', ['groups' => 'admin_question'])]);

            } else {
                throw new \Exception(sprintf('Вопрос с идентификатором %s не обнаружен', $questionId));
            }

        } catch (\Exception $e) {
            return $this->errorResponse->response(['error' => $e->getMessage()]);
        }

    }

    public function getAll(): JsonResponse
    {

//        todo убрать костыль
        try {
            $pagination = $this->paginator->getPagination($this->repository->findLastUpdatedQuery());
            if ($pagination->count() > 0) {
                $content = $this->questionHandler->getAll($pagination->getItems(), 'json', ['groups' => 'admin_question']);
                return $this->successResponse->response(['content' => '{ "question":' . $content . '}, "pagination":' . json_encode($this->paginator->getInfo($pagination)) . '}']);
//            return $this->successResponse->response(['content' => $content .','. json_encode(['pagination' => $this->paginator->getInfo($pagination)])]);

//            return $this->successResponse->response(['content' => $questions], false);

            }
            return $this->successResponse->response(['content' => '{"pagination":' . json_encode($this->paginator->getInfo($pagination)) . '}']);

        } catch (\Exception $e) {
            return $this->errorResponse->response(['error' => $e->getMessage()]);
        }
    }


}