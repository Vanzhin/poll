<?php

namespace App\Factory\Answer;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Result;

class AnswerBuilder
{
    public function __construct()
    {
    }

    public function buildAnswer(array $content, Question $question, Result $result): Answer
    {
        $answer = new Answer();
        $answer->setQuestion($question)->setResult($result)->setContent($content);

        return $answer;
    }

}