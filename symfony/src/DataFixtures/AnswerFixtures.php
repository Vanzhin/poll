<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Result;
use App\Entity\Ticket;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AnswerFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Answer::class, 500, function (Answer $answer) use ($manager) {
            $answer
                ->setQuestion($this->getRandomReference(Question::class))
                ->setResult($this->getRandomReference(Result::class));
            $content = $this->faker->boolean(75) ? $answer->getQuestion()->getAnswer() : [];
            $answer->setContent($content);
        });

        foreach ($this->referenceRepository->getReferencesByClass()[Result::class] as $resultReference) {
            $score = 0;
            $tickets = [];
            foreach ($resultReference->getAnswers() as $answer) {
                if ($answer->getContent() === $answer->getQuestion()->getAnswer()) {
                    $score++;
                }
                foreach ($answer->getQuestion()->getTickets() as $ticket) {
                    $tickets[] = $ticket->getId();
                }

            }
            $result = $manager->find(Result::class, $resultReference->getId());

            if (count(array_unique($tickets)) === 1) {

                $ticket = $manager->find(Ticket::class, $tickets[0]);
                $result->setTicket($ticket);
            }
            $result->setScore($score);

            $manager->persist($result);
            $manager->flush();

        };
    }

    public function getDependencies(): array
    {
        return [
            QuestionFixtures::class,
            ResultFixtures::class,
        ];
    }
}
