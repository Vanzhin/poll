<?php

namespace App\Controller\Api\User\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Company;
use App\Factory\Profile\ProfileFactory;
use App\Factory\User\UserFactory;
use App\Response\AppException;
use App\Service\RoleService;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CreateAction extends NewBaseAction
{
    public function __construct(
        SerializerService                       $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidationService      $validator,
        private readonly UserFactory            $userFactory,
        private readonly ProfileFactory         $profileFactory,
        private readonly RoleService            $roleService,

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
            if (!empty($errors = $this->validator->validate($profile))) {
                throw new AppException(implode(', ', $errors));
            }
        }
        if (!$this->roleService->check($data['roles'] ?? [])) {
            throw new  AccessDeniedException('Вам не разрешено назначать такую роль для пользователя');
        };
        if (isset($data['roles']) && is_array($data['roles'])) {

            $data['roles'] = $this->roleService->getRoles($data['roles']);
        }
        $user = $this->userFactory->createBuilder()->buildCompanyUser($data, $company, null, $profile);
        if (!empty($errors = $this->validator->validate($user))) {
            throw new AppException(implode(', ', $errors));
        }
        $errors = [];
        if ($errors = $this->validator->userPasswordValidate($data['password'])) {
            foreach ($errors as $error) {
                $errors[] = $error;
            }
        }
        if ($data['password'] !== $data['confirmPassword']) {
            $errors[] = 'Пароли не совпадают.';
        }
        if ($errors) {
            throw new AppException(implode(', ', $errors));
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->successResponse($user, ['user_editable'], 'Пользователь создан');
    }
}