<?php

namespace App\Factory\Question;

use Doctrine\ORM\EntityManagerInterface;

class QuestionFactory
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    public function createBuilder(): QuestionBuilder
    {
        return new QuestionBuilder($this->em);
    }

}