<?php

namespace App\Factory\MinTrudTest;

use Doctrine\ORM\EntityManagerInterface;

class MinTrudTestFactory
{
    public function createBuilder(): MinTrudTestBuilder
    {
        return new MinTrudTestBuilder();
    }

}