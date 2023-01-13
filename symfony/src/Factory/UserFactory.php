<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{


    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function create(string $email, string $password = '123456789')
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );
        return $user;
    }

}