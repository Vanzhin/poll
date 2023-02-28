<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Section;
use App\Entity\Test;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SectionFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {

        $this->createMany(Section::class, 100, function (Section $section) use ($manager) {
            $test = $this->getRandomReference(Test::class);
            if ($test->getQuestion()->count() > 0) {
                $question = $test->getQuestion()->toArray()[array_rand($test->getQuestion()->toArray())];
                $section->addQuestion($question);
            };
            $section
                ->setTitle($this->faker->realText($this->faker->numberBetween(10, 50), true))
                ->setTest($test);
        });
    }


    public function getDependencies(): array
    {
        return [
            TestFixtures::class,
            QuestionFixtures::class,
        ];
    }
}
