<?php

namespace App\Factory\MinTrudTest;

use App\Entity\MinTrudTest;

class MinTrudTestBuilder
{
    public function __construct()
    {
    }

    public function createOrUpdateMinTrudTest(array $data, MinTrudTest $test = null): MinTrudTest
    {
        if (!$test) {
            $test = new MinTrudTest();
        }
        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $test->setTitle($item);
                continue;
            };
            if ($key === 'originalId') {
                $test->setOriginalId($item);
                continue;

            };
        }

        return $test;
    }

}