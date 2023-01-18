<?php

namespace App\DataFixtures;

use App\Entity\Section;
use App\Entity\Test;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Test::class, 100, function (Test $test) use ($manager) {
            $test
                ->setTitle($this->faker->colorName() . '-' . $this->faker->company())
                ->setSection($this->getRandomReference(Section::class));
        });
    }

    public function getDependencies(): array
    {
        return [
            SectionFixtures::class,
        ];
    }
}
