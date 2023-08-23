<?php

namespace App\Controller\Api\Group\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Repository\Group\Mapper\GroupFilterMapper;
use App\Repository\Interfaces\GroupRepositoryInterface;
use App\Response\AppException;
use App\Service\Paginator;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ListAction extends NewBaseAction
{
    public function __construct(
        SerializerService                  $serializer,
        private readonly ValidationService $validation,
        private readonly GroupFilterMapper $mapper,
        private readonly GroupRepositoryInterface $repository,
        private readonly Paginator $paginator,
        private readonly Security $security,
    )
    {
        parent::__construct($serializer);
    }

    public function run(array $data): JsonResponse
    {
        $errors = $this->validation->dataValidate($data, $this->mapper->getValidationCollection());

        if ($errors) {
            throw new AppException(implode(', ', $errors));
        }
        $filter = $this->mapper->buildFilter($data);
        $pagination = $this->paginator->getPagination($this->repository->buildFilter($filter, $this->security->getUser()->getCompany()));
        if ($pagination->count() <= 0) {
            throw new NotFoundHttpException();

        }
//        foreach ($pagination as $user){
//            $user->setPermissions(
//                new Permissions(
//                    $this->security->isGranted(UserVoter::EDIT, $user),
//                    $this->security->isGranted(UserVoter::DELETE, $user)
//                )
//            );
//        }
        return $this->successResponse(
            [
                "list" => $pagination,
                "pagination" => $this->paginator->getInfo($pagination)
            ],
            ['admin_group']
        );
    }
}