<?php

namespace App\Factory\User;

use App\Enum\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct
    (
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly EntityManagerInterface      $em,

    )
    {
    }

    public function createBuilder(): UserBuilder
    {
        return new UserBuilder($this->em, $this->userPasswordHasher);
    }

}