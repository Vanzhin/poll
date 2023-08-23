<?php

namespace App\Factory\Question;

use App\Entity\Question;
use App\Entity\Section;
use App\Entity\Test;
use App\Entity\Ticket;
use App\Entity\Type;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;

class QuestionBuilder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function buildQuestion(array $data = [], User $user = null, Question $question = null): Question
    {
        if (!$question) {
            $question = new Question();
        }

        if(!array_key_exists('published',$data) ){
            $data['published'] = false;
        }
        foreach ($data as $key => $item) {
            if ($key === 'shuffleVariants') {
                $question->setShuffleVariants(filter_var($item, FILTER_VALIDATE_BOOLEAN));
                continue;
            };

            if ($key === 'title') {
                $question->setTitle($item);
                continue;
            };
            if ($key === 'type' && $this->em->getRepository(Type::class)->findOneBy(['title' => $item])) {
                $question->setType($this->em->getRepository(Type::class)->findOneBy(['title' => $item]));
                continue;

            };
            if ($key === 'ticket') {
                foreach ($question->getTickets() as $ticket) {
                    $question->removeTicket($ticket);
                }
                foreach ($item as $ticketId) {
                    $ticket = $this->em->getRepository(Ticket::class)->find($ticketId);
                    if ($ticket) {
                        $question->addTicket($ticket);
                    }

                }
                continue;

            }
            if ($key === 'section') {
                $question->setSection($this->em->getRepository(Section::class)->find($item));
                continue;

            }
            if ($key === 'test') {
                $question->setTest($this->em->getRepository(Test::class)->find($item));
                continue;

            }
            if ($key === 'published') {
                if(filter_var($item, FILTER_VALIDATE_BOOLEAN)){
                    $question->setPublishedAt(new \DateTime('now'));
                }else{
                    $question->setPublishedAt(null);

                }
                continue;
            }
            if ($key === 'image') {
                $question->setImage($item);
                continue;
            };
        }
        if($user){
            $question->setAuthor($user);

        }
        return $question;
    }

}