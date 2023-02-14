<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

abstract class BaseFixtures extends Fixture
{
    protected \Faker\Generator $faker;
    protected ObjectManager $manager;
    private array $referencesIndex = [];


    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create("ru_RU");
        $this->manager = $manager;
        $this->loadData($manager);


    }

    abstract function loadData(ObjectManager $manager);

    protected function create(string $className, callable $factory)
    {

        $entity = new $className();
        $factory($entity);
        $this->manager->persist($entity);
        return $entity;

    }

    protected function createMany(string $className, int $count, callable $factory)
    {
        $start = isset($this->referenceRepository->getReferencesByClass()[$className]) ? count($this->referenceRepository->getReferencesByClass()[$className]) : 0;

//        $start = count($this->referencesIndex[$className]);
//        dd($this->referencesIndex,$className, isset($this->referencesIndex[$className]), $start );
        for ($i = $start; $i < $count + $start; $i++) {
            $entity = $this->create($className, $factory);
            $this->addReference("$className|$i", $entity);
        }
        $this->manager->flush();
    }

    protected function getRandomReference($className): object
    {

        if (!isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];
            foreach ($this->referenceRepository->getReferencesByClass()[$className] as $key => $reference) {
                if (stripos($key, $className . '|') === 0) {

                    $this->referencesIndex[$className][] = $key;
                }
            }
        }
        if (empty($this->referencesIndex[$className])) {
            throw new \Exception('не найдены ссылки на класс ' . $className);
        }
        return $this->getReference($this->faker->randomElement($this->referencesIndex[$className]));
    }

}
