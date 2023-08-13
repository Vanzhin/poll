<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Test;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $parents = $this->referenceRepository->getReferencesByClass()[Category::class];
        $parentsLevel2 = [];
        foreach ($parents as $parent) {
            if ($parent->getParent() && $parent->getLevel() === 2) {
                $parentsLevel2[] = $parent;
            }
        }

        $this->createMany(Test::class, 250, function (Test $test) use ($manager, $parentsLevel2) {
            $parentLevel2 = $parentsLevel2[array_rand($parentsLevel2)];
            $category = $this->manager->find(Category::class, $parentLevel2->getId());

            $test
                ->setTitle($this->faker->colorName() . '-' . $this->faker->company())
                ->setCategory($category)
                ->setDescription($this->faker->sentence())
                ->setTime(1200)
                ->setSectionCountToPass(1)
                ->setAlias($this->faker->word)
            ;

        });
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
