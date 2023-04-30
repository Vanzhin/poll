<?php

namespace App\Factory\Test;

use App\Entity\Category;
use App\Entity\MinTrudTest;
use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;

class TestBuilder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function updateOrCreate(array $data, ?Test $test = null): Test
    {
        $test = $test ?? new Test();

        if (!isset($data['minTrud']) && $test->getMinTrudTest()) {
            $test->setMinTrudTest(null);
        }

        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $test->setTitle($item);
                continue;
            };
            if ($key === 'alias') {
                $test->setAlias($item);
                continue;
            };
            if ($key === 'description') {
                $test->setDescription($item);
                continue;

            };
            if ($key === 'time') {
                $test->setTime(intval($item));
                continue;

            };
            if ($key === 'sectionCountToPass') {
                $test->setSectionCountToPass(intval($item));
                continue;

            };
            if ($key === 'minTrud') {
                $minTrud = $this->em->find(MinTrudTest::class, $item);
                if ($minTrud) {
                    $test->setMinTrudTest($minTrud);

                }
                continue;

            };
            if ($key === 'category' && $this->em->find(Category::class, $item)) {
                $test->setCategory($this->em->find(Category::class, $item));

            };

        }
        if (!isset($data['time']) && !$test->getTime()) {
            $test->setTime(6000);
        }

        return $test;
    }


}