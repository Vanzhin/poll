<?php

namespace App\Controller\Api\User\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Company;
use App\Factory\Profile\ProfileFactory;
use App\Factory\User\UserFactory;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreateAction extends NewBaseAction
{
    public function __construct(
        SerializerService                       $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidationService      $validator,
        private readonly UserFactory            $userFactory,
        private readonly ProfileFactory         $profileFactory

    )
    {
        parent::__construct($serializer);
    }

    public function run(Request $request, Company $company): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $profile = null;
        if (isset($data['profile'])) {
            $profile = $this->profileFactory->createBuilder()->buildProfile($data['profile']);
            if (!empty($this->validator->validate($profile))) {
                throw new \Exception(implode(', ', $this->validator->validate($profile)));
            }
        }

        $user = $this->userFactory->createBuilder()->buildCompanyUser($data, $company, null, $profile);
        if (!empty($this->validator->validate($user))) {
            throw new \Exception(implode(', ', $this->validator->validate($user)));
        }
        $errors =[];
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