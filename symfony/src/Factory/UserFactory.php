<?php

namespace App\Factory;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct
    (
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    )
    {
    }

    public function create(string $email, string $password = null, string $firstName = null): ?User
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