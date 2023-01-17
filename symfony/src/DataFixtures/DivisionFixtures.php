<?php

namespace App\DataFixtures;

use App\Entity\Division;
use Doctrine\Persistence\ObjectManager;

class DivisionFixtures extends BaseFixtures
{

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Division::class, 10, function (Division $division) use ($manager) {
            $division
                ->setTitle($this->faker->city . " ". $this->faker->country);


        });
    }
}
