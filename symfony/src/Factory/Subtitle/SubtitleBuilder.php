<?php

namespace App\Factory\Subtitle;

use App\Entity\Question;
use App\Entity\Subtitle;
use App\Entity\Variant;
use Doctrine\ORM\EntityManagerInterface;

class SubtitleBuilder
{
    public function __construct()
    {
    }

    public function buildSubtitle(string $title = null, Question $question = null, Variant $correct = null, Subtitle $subtitle = null): Subtitle
    {
        if (!$subtitle) {
            $subtitle = new Subtitle();
        }

        if ($title) {
            $subtitle->setTitle($title);
        }

        if ($question) {
            $subtitle->setQuestion($question);
        }

        if ($correct) {
            $subtitle->setCorrect($correct);

        }

        return $subtitle;
    }

}