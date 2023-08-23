<?php

namespace App\Repository\Interfaces;

use App\Repository\Question\Filter\QuestionFilter;
use Doctrine\ORM\QueryBuilder;

interface QuestionRepositoryInterface
{
    public function buildFilter(QuestionFilter $filter): QueryBuilder;
}