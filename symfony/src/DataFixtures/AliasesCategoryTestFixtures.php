<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Test;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AliasesCategoryTestFixtures extends BaseFixtures implements FixtureGroupInterface
{


    function loadData(ObjectManager $manager)
    {
        $categories = $manager->getRepository(Category::class)->findAll();
        $tests = $manager->getRepository(Test::class)->findAll();

        /** @var Category $category */
        foreach ($categories as $category) {
            $category->setAlias(mb_substr($category->getTitle(), 0, 30));
            $manager->persist($category);

        }

        foreach ($tests as $test) {
            $test->setAlias(mb_substr($test->getTitle(), 0, 30));
            $manager->persist($test);

        }
        $manager->flush();

    }

    public function getDependencies(): array
    {
        return [
            TestFixtures::class,
            CategoryFixtures::class
        ];
    }

    public static function getGroups(): array
    {
        return ['alias'];
    }
}
