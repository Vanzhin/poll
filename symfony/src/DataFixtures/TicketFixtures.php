<?php

namespace App\DataFixtures;

use App\Entity\Section;
use App\Entity\Test;
use App\Entity\Ticket;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TicketFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Ticket::class, 500, function (Ticket $ticket) use ($manager) {
            $ticket
                ->setTitle('Билет № ' . $this->faker->numberBetween(1, 50))
                ->setTest($this->getRandomReference(Test::class));
        });
    }

    public function getDependencies(): array
    {
        return [
            TestFixtures::class,
        ];
    }
}
