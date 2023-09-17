<?php

namespace App\Controller\Api\Admin\Protocol\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Repository\Interfaces\ProtocolRepositoryInterface;
use App\Repository\Protocol\Mapper\ProtocolFilterMapper;
use App\Response\AppException;
use App\Service\Paginator;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ListAction extends NewBaseAction
{
    public function __construct(SerializerService                            $serializer,
                                private readonly ValidationService           $validation,
                                private readonly Paginator                   $paginator,
                                private readonly ProtocolRepositoryInterface $repository)
    {
        parent::__construct($serializer);
    }

    public function run(Request $request): JsonResponse
    {
        $mapper = new ProtocolFilterMapper();
        $data = array_merge($request->query->all() ?? [], json_decode($request->getContent(), true) ?? []);
        $errors = $this->validation->dataValidate($data, $mapper->getValidationCollection());

        if ($errors) {
            throw new AppException(implode(', ', $errors));
        }
        $filter = $mapper->buildFilter($data);
        $pagination = $this->paginator->getPagination($this->repository->buildFilter($filter));
        if ($pagination->count() <= 0) {
            throw new NotFoundHttpException();

        }
        return $this->successResponse(
            [
                "list" => $pagination,
                "pagination" => $this->paginator->getInfo($pagination)
            ],
            ['admin_protocol']
        );
    }
}