<?php

namespace App\Handler;

use App\Entity\User;
use App\Factory\User\UserFactory;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserHandler
{


    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher,
                                private readonly ValidationService           $validation,
                                private readonly UserFactory                 $userFactory,
                                private readonly EntityManagerInterface      $em)
    {
    }

    /**
     * @throws \Exception
     */
    public function edit(User $user, array $data): array
    {

        try {
            if (($data['oldPassword'] ?? null)) {
                if ($this->userPasswordHasher->isPasswordValid($user, $data['oldPassword'])) {
                    if (($data['newPassword'] ?? null) === ($data['confirmNewPassword'] ?? null)) {
                        if (!is_null($this->validation->userPasswordValidate($data['newPassword']))) {
                            throw new \Exception(implode(', ', $this->validation->userPasswordValidate($data['newPassword'])), 422);
                        } else {
                            $this->userFactory->createBuilder()->updateUserPassword($user, $data['newPassword']);

                        }
                    } else {
                        throw new \Exception('Пароли не совпадают', 422);
                    }
                } else {
                    throw new \Exception('Старый пароль указан не верно', 422);
                }
            }

            $this->userFactory->createBuilder()->updateUser($user, $data ?? []);
            if (count($this->validation->validate($user)) > 0) {
                throw new \Exception(implode(', ', $this->validation->validate($user)), 422);

            } else {
                $this->em->persist($user);
                $this->em->flush();

                return ['message' => 'Данные пользователя обновлены'];
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 422);

        }

    }

}