<?php

namespace App\DataFixtures;

use App\Entity\Result;
use App\Entity\Test;
use App\Entity\User\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ResultFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Result::class, 50, function (Result $result) use ($manager) {
            $result
                ->setScore(0)
                ->setUser($this->getRandomReference(User::class));

            $test = $this->getRandomReference(Test::class);
            while (($test->getQuestion()->count()) === 0) {
                $test = $this->getRandomReference(Test::class);
            }
            $result->setTest($test);
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
