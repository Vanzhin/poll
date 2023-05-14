<?php

namespace App\Repository\Filter\vo\test;

class DateTime extends \DateTimeImmutable implements \JsonSerializable
{

    public function jsonSerialize(): string
    {
        return $this->format($this::ATOM);
    }
}