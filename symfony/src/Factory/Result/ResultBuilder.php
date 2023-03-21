<?php

namespace App\Factory\Result;

use App\Entity\Question;
use App\Entity\Result;
use App\Entity\Test;
use App\Entity\Ticket;
use App\Entity\User;
use App\Enum\Mode;
use Doctrine\ORM\EntityManagerInterface;

class ResultBuilder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function buildResult(array $data, User $user, Result $result = null): Result
    {
        if (!$result) {
            $result = new Result();
        }
        $result->setUser($user);
        foreach ($data as $key => $item) {
            switch ($key) {
                case 'test':
                    $result->setTest($this->em->find(Test::class, $item));
                    break;
                case 'mode':
                    $result->setMode(Mode::fromName($item));
                    break;
                case 'ticket':
                    $result->setTicket($this->em->find(Ticket::class, $item));
                    break;
                case 'score':
                    $result->setScore($item);
                    break;
            };

            if (!$result->getScore()) {
                $result->setScore(0);

            }

        }
        return $result;
    }


}