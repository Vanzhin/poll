<?php

namespace App\DataFixtures;

use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixtures implements FixtureGroupInterface
{

    private $params;
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher, ParameterBagInterface $params)
    {
        $this->hasher = $hasher;
        $this->params = $params;
    }
    public function loadData(ObjectManager $manager): void
    {
        $this->create(User::class, function (User $user) use ($manager) {
            $user
                ->setFirstName($this->params->get('app.admin_user'))
                ->setEmail($this->params->get('app.admin_email'))
                ->setPassword($this->hasher->hashPassword($user, $this->params->get('app.admin_pass')))
                ->setRoles(['ROLE_ADMIN']);
        });

        $this->createMany(User::class, 10, function (User $user) use ($manager) {
            $user
                ->setFirstName($this->faker->firstName())
                ->setEmail($this->faker->email())
                ->setPassword($this->hasher->hashPassword($user, $this->params->get('app.user_pass')));

        });
    }

    public static function getGroups(): array
    {
        return ['prod'];
    }
}
