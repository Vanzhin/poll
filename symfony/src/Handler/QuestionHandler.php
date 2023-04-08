<?php

namespace App\Handler;

use App\Entity\Question;
use App\Service\SerializerService;

class QuestionHandler
{

    public function __construct(private readonly SerializerService $serializer)
    {
    }

    public function get(Question $question, string $format, array $groups): string
    {
        return $this->serializer->serializeObject($question, $format, $groups);

    }
}