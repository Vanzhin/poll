<?php

namespace App\Factory;

use Doctrine\ORM\EntityManagerInterface;

class TicketFactory
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    public function createBuilder(): TicketBuilder
    {
        return new TicketBuilder($this->em);
    }

}