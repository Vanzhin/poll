<?php

namespace App\Factory\User;

use App\Entity\User\User;
use App\Entity\User\vo\WorkerCard;
use App\Enum\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserBuilder
{
    public function __construct(private readonly EntityManagerInterface      $em,
                                private readonly UserPasswordHasherInterface $userPasswordHasher,
    )
    {
    }

    public function updateUserRole(array $data, UserInterface $user): UserInterface
    {
        foreach ($data as $key => $item) {
            if ($key === 'role' && is_array($item)) {
                $userRoles = [];
                foreach ($item as $role) {
                    $allRoles = array_column(Role::cases(), 'name');
                    $roleToAttach = Role::tryFrom($role)?->name;
                    if (in_array($roleToAttach, $allRoles)) {
                        $userRoles[] = $roleToAttach;
                    }
                }
                $user->setRoles(array_unique($userRoles));
            }
        }

        return $user;
    }

    public function buildUser(string $email, string $password = null, string $firstName = null): ?User
    {
        $user = new User();
        $user->setEmail($email)->setFirstName($firstName);
        if ($password) {
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
            );
        }
        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        foreach ($data as $key => $item) {
            if ($key === 'firstName') {
                $user->setFirstName($item);
                continue;
            };
            if ($key === 'email') {
                $user->setEmail($item);
                continue;
            };

        }

        return $user;

    }

    public function createWorker(User $user): WorkerCard
    {
        $worker = new WorkerCard();

        return $worker;
    }

    public function updateUserPassword(User $user, string $plainPassword): User
    {
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $plainPassword
            )
        );
        return $user;

    }


}