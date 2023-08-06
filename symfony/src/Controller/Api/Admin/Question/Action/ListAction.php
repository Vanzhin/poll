<?php

namespace App\Controller\Api\Admin\Question\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Repository\Interfaces\QuestionRepositoryInterface;
use App\Repository\Question\Mapper\QuestionFilterMapper;
use App\Service\Paginator;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ListAction extends NewBaseAction
{
    public function __construct(
        SerializerService                  $serializer,
        private readonly ValidationService $validation,
        private readonly Paginator         $paginator,
        private readonly QuestionRepositoryInterface $repository,

    )
    {
        parent::__construct($serializer);
    }

    public function run(array $data): JsonResponse
    {
        $mapper = new QuestionFilterMapper();
        $errors = $this->validation->dataValidate($data, $mapper->getValidationCollection());

        if ($errors) {
            throw new \Exception(implode(', ', $errors));
        }
        $filter = $mapper->buildFilter($data);
////        todo исправить $pagination, чтобы задавать $page $limit параметрами с наружи
        $pagination = $this->paginator->getPagination($this->repository->buildFilter($filter));
        if ($pagination->count() <= 0) {
            throw new NotFoundHttpException();
        }
        return $this->successResponse(
            [
                "list" => $pagination,
                "pagination" => $this->paginator->getInfo($pagination)
            ],
            ['admin_question']
        );
    }
}