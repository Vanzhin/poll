<?php

namespace App\Factory\Answer;


class AnswerFactory
{

    public function createBuilder(): AnswerBuilder
    {
        return new AnswerBuilder();
    }
}