<?php

namespace App\Factory\Ticket;

use App\Entity\Question;
use App\Entity\Test;
use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;

class TicketBuilder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function buildTicket(array $data, Ticket $ticket = null): Ticket
    {
        if (!$ticket) {
            $ticket = new Ticket();
        }
        foreach ($data as $key => $item) {
            switch ($key) {
                case 'title':
                    $ticket->setTitle(null);
                    if(is_numeric($item)){
                        $ticket->setTitle($item);
                    }
                    break;
                case 'description':
                    $ticket->setDescription($item);
                    break;
                case 'test':
                    $ticket->setTest(null);
                    $test = $this->em->find(Test::class, $item);
                    if ($test) {
                        $ticket->setTest($test);
                        $lastTicketTitle = $this->em->getRepository(Ticket::class)->findLastTitleByTest($test);
                        if($lastTicketTitle){
                            $ticket->setTitle(++$lastTicketTitle);
                        }else{
                            $ticket->setTitle(1);
                        }
                    };

                    break;
                case 'question':
                    $ticket->getQuestion()->clear();
                    foreach ($item as $question) {
                        if ($this->em->find(Question::class, $question)) {
                            $ticket->addQuestion($this->em->find(Question::class, $question));
                        };
                    }
                    break;
            };

        }
        return $ticket;
    }

}