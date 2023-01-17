<?php

namespace App\DataFixtures;

use App\Entity\Division;
use App\Entity\Section;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SectionFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {

        $this->createMany(Section::class, 100, function (Section $section) use ($manager) {
            $section
                ->setTitle($this->faker->realText($this->faker->numberBetween(10, 50), true) . $this->faker->numberBetween(10, 1000))
                ->setDivision($this->getRandomReference(Division::class));
        });
    }


    public function getDependencies()
    {
        return [
            DivisionFixtures::class,
        ];
    }
}
