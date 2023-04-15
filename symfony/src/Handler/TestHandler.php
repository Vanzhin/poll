<?php

namespace App\Handler;

use App\Service\SerializerService;

class TestHandler
{
    public function __construct(private readonly SerializerService $serializer)
    {
    }


    public function getAll(array $tests, string $format, array $groups): string
    {
        return $this->serializer->serializeMany([$tests], $format, $groups);

    }
}