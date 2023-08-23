<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class ResultService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

//    public function save(User $user, Question $question, array $userAnswer, bool $score, Result $result): void
//    {
//        //todo доработать добавление билета в результат
//        if (!$result->getUser()) {
//            $result->setUser($user);
//        }
//        $answer = new Answer();
//        $answer->setResult($result)
//            ->setQuestion($question)
//            ->setContent($userAnswer);
//        if ($score) {
//            $totalScore = $result->getScore() + 1;
//        } else {
//            $totalScore = $result->getScore();
//        }
//
//        $result->setScore($totalScore ?? 0);
//        $this->em->persist($result);
//        $this->em->persist($answer);
//        $this->em->flush();
//    }

}