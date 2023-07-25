<?php

namespace App\Controller\Api\Registration\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Factory\User\UserFactory;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Response\AppException;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserRegisterAction extends NewBaseAction
{
    public function __construct(
        SerializerService                        $serializer,
        private readonly ValidationService       $validation,
        private readonly UserFactory             $userFactory,
        private readonly EntityManagerInterface  $entityManager,
        private readonly UserRepositoryInterface $repository,


    )
    {
        parent::__construct($serializer);
    }

    public function run(array $registerData): JsonResponse
    {
        if ($errors = $this->validation->userPasswordValidate($registerData['password'])) {
            throw new AppException(implode(',', $errors));
        }

        if ($registerData['password'] !== $registerData['confirmPassword']) {
            throw new AppException('Пароли не совпадают.');
        }

        $user = $this->userFactory->createBuilder()->buildUser($registerData['email'] ?? '', $registerData['login'], $registerData['password'] ?? '');
        if ($errors = $this->validation->validate($user)) {
            throw new AppException(implode(',', $errors));
        }

        if (count($this->repository->findAllByEmail($registerData['email'])) > 0) {
            throw new AppException('Пользователь с указанной почтой уже существует');
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->successResponse([], [], 'Пользователь зарегистрирован');
    }
}