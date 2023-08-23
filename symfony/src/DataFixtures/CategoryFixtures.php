<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends BaseFixtures
{
    function loadData(ObjectManager $manager)
    {
        $this->createMany(Category::class, 10, function (Category $category) use ($manager) {
            $alias = $this->faker->word();
            $category->setTitle($alias);
            $category->setDescription($this->faker->sentence())
                ->setAlias($alias);

        });
        $this->createMany(Category::class, 50, function (Category $category) use ($manager) {

            $parents = $this->referenceRepository->getReferencesByClass()[Category::class];
            $parentsLevel0 = [];
            foreach ($parents as $parent) {
                if (is_null($parent->getParent())) {
                    $parentsLevel0[] = $parent;
                }
            }
            $parentLevel1 = $parentsLevel0[array_rand($parentsLevel0)];

            $category
                ->setTitle($this->faker->realText($this->faker->numberBetween(10, 50), true) . $this->faker->numberBetween(10, 1000)
                );
            $category
                ->setDescription($this->faker->sentence())
                ->setParent($parentLevel1);
            $category->setAlias($this->faker->word());

        });

        $this->createMany(Category::class, 250, function (Category $category) use ($manager) {

            $parents = $this->referenceRepository->getReferencesByClass()[Category::class];
            $parentsLevel1 = [];
            foreach ($parents as $parent) {
                if ($parent->getParent() && is_null($parent->getParent()->getParent())) {
                    $parentsLevel1[] = $parent;
                }
            }
            $parentLevel1 = $parentsLevel1[array_rand($parentsLevel1)];
            $title = $this->faker->realText($this->faker->numberBetween(10, 50), true) . $this->faker->numberBetween(10, 1000);
            $category
                ->setTitle($title);
            $category
                ->setDescription($this->faker->sentence())
                ->setParent($parentLevel1);
            $category->setAlias($this->faker->word());

        });
    }

}
