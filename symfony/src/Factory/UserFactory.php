<?php

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(private readonly UserRepository $repository,
                                private readonly UserPasswordHasherInterface $userPasswordHasher,
                                private readonly EntityManagerInterface $entityManager)
    {
    }

    public function create(string $email, string $password = '123456789'): ?User
    {
        $user = $this->repository->findOneBy(['email' => $email]);
        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
            );
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        return $user;
    }
}