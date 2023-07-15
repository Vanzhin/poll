<?php

namespace App\Controller\Api\User\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\User\User;
use App\Factory\User\UserFactory;
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
        private readonly ValidationService      $validator,
        private readonly UserFactory            $userFactory,
    )
    {
        parent::__construct($serializer);
    }

    public function run(User $user, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $company = $user->getCompany();
        $user = $this->userFactory->createBuilder()->buildCompanyUser($data, $company, $user);
        $errors = $this->validator->validate($user);
        if ($this->validator->userPasswordValidate($data['password'])) {
            foreach ($this->validator->userPasswordValidate($data['password']) as $error) {
                $errors[] = $error;

            }
        }
        if ($data['password'] !== $data['confirmPassword']) {
            $errors[] = 'Пароли не совпадают.';
        }
        if ($errors) {
            throw new \Exception(implode(', ', $errors));
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->successResponse($user, ['user_editable']);
    }
}