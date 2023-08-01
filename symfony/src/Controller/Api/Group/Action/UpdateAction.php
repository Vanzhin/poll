<?php

namespace App\Controller\Api\Group\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Group;
use App\Entity\User\User;
use App\Factory\Group\GroupFactory;
use App\Response\AppException;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UpdateAction extends NewBaseAction
{
    public function __construct(
        SerializerService                       $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly GroupFactory           $factory,
        private readonly ValidationService      $validator,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Request $request, Group $group, User $owner): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $errors = $this->validator->dataValidate($data, $this->factory->getValidationCollection());
        if (!empty($errors)) {
            throw new AppException(implode(', ', $errors));
        }

        $group = $this->factory->createBuilder()->build($data, null, $group);
        if ($errors = $this->validator->validate($group)) {
            throw new AppException(implode(', ', $errors));
        }
        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return $this->successResponse($group, ['admin_group'], 'Группа обновлена');
    }
}