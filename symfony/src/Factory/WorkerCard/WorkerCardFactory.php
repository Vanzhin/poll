<?php

namespace App\Factory\WorkerCard;

class WorkerCardFactory
{
    public function __construct
    (

    )
    {
    }

    public function createBuilder(): WorkerCardBuilder
    {
        return new WorkerCardBuilder();
    }

}