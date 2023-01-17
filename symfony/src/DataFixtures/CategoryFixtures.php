<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends BaseFixtures
{

    function loadData(ObjectManager $manager)
    {

        $this->createMany(Category::class, 10, function (Category $category) use ($manager) {
            $category
                ->setTitle($this->faker->realText($this->faker->numberBetween(10,30),true));

        });

    }
}
