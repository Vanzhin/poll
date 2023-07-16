<?php

namespace App\Controller\Api\User\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\User\Mapper\UserFilterMapper;
use App\Service\Paginator;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ListAction extends NewBaseAction
{
    public function __construct(
        SerializerService                        $serializer,
        private readonly ValidationService       $validation,
        private readonly UserRepositoryInterface $repository,
        private readonly Paginator               $paginator,
        private readonly Security                $security,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Request $request): JsonResponse
    {

        $mapper = new UserFilterMapper();
        $data = array_merge($request->query->all() ?? [], json_decode($request->getContent(), true) ?? []);
        $errors = $this->validation->dataValidate($data, $mapper->getValidationCollection());

        if ($errors) {
            throw new \Exception(implode(', ', $errors));
        }
        $filter = $mapper->buildFilter($data);
////        todo исправить $pagination, чтобы задавать $page $limit параметрами с наружи
        $pagination = $this->paginator->getPagination($this->repository->buildFilter($filter, $this->security->getUser()->getCompany()));
        if ($pagination->count() <= 0) {
            throw new NotFoundHttpException();

        }
        return $this->successResponse(
            [
                "list" => $pagination,
                "pagination" => $this->paginator->getInfo($pagination)
            ],
            ['user_editable']
        );
    }
}