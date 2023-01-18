<?php

namespace App\DataFixtures;

use App\Entity\Result;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ResultFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Result::class, 500, function (Result $result) use ($manager) {
            $result
                ->setScore(0)
                ->setUser($this->getRandomReference(User::class));
        });

    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TicketFixtures::class,
        ];
    }
}
