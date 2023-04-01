<?php

namespace App\Factory\User;

use App\Entity\User;
use App\Enum\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserBuilder
{
    public function __construct(private readonly EntityManagerInterface      $em,
                                private readonly UserPasswordHasherInterface $userPasswordHasher,
    )
    {
    }

    public function updateUser(array $data, User $user): User
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

}