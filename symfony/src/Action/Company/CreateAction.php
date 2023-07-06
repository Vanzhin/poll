<?php

namespace App\Action\Company;

use App\Action\BaseAction;
use App\Factory\Company\CompanyFactory;
use App\Factory\User\UserFactory;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreateAction extends BaseAction
{
    public function __construct(
        SerializerService                       $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidationService      $validator,
        private readonly UserFactory            $userFactory,
        private readonly CompanyFactory         $companyFactory,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $user = $this->userFactory->createBuilder()->buildUser($data['email'] ?? '');
            $errors = $this->validator->validate($user);
            if (isset($data['password'])) {
                foreach ($this->validator->userPasswordValidate($data['password']) as $error) {
                    $errors[] = $error;
                }
            }
            if ($errors) {
                throw new \Error(implode(', ', $errors));
            }
            $user->setRoles(['ROLE_ADMIN']);
            $data['user'] = $user;
            $company = $this->companyFactory->createBuilder()->buildCompany($data);
            $errors = $this->validator->validate($company);
            $user->setCompany($company);

            if ($errors) {
                throw new \Error(implode(', ', $errors));
            }
            $this->entityManager->persist($user);
            $this->entityManager->persist($company);
            $this->entityManager->flush();

            return $this->successResponse($company, ['admin_user']);

        } catch (\Exception|\Error $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

}