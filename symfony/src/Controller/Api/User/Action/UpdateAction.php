<?php

namespace App\Controller\Api\User\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\User\User;
use App\Factory\Profile\ProfileFactory;
use App\Factory\User\UserFactory;
use App\Service\RoleService;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UpdateAction extends NewBaseAction
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

    public function run(User $user, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $company = $user->getCompany();
        $profile = $this->profileFactory->createBuilder()->buildProfile($data['profile'] ?? [], $user->getProfile());
        if (!empty($this->validator->validate($profile))) {
            throw new \Exception(implode(', ', $this->validator->validate($profile)));
        }
        if (!$this->roleService->check($data['roles'] ?? [])) {
            throw new  AccessDeniedException('Вам не разрешено назначать такую роль для пользователя');
        };
        if (isset($data['roles']) && is_array($data['roles'])) {

            $data['roles'] = $this->roleService->getRoles($data['roles']);
        }
        $user = $this->userFactory->createBuilder()->buildCompanyUser($data ?? [], $company, $user, $profile);
        if (!empty($this->validator->validate($user))) {
            throw new \Exception(implode(', ', $this->validator->validate($user)));
        }
        $errors = [];
        if (isset($data['password'])) {
            if ($this->validator->userPasswordValidate($data['password'])) {
                foreach ($this->validator->userPasswordValidate($data['password']) as $error) {
                    $errors[] = $error;
                }
            }
            if ($data['password'] !== $data['confirmPassword']) {
                $errors[] = 'Пароли не совпадают.';
            }
        }

        if ($errors) {
            throw new \Exception(implode(', ', $errors));
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->successResponse($user, ['user_editable'], 'Пользователь обновлен');
    }
}