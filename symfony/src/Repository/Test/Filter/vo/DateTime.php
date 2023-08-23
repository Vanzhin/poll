<?php

namespace App\Repository\Test\Filter\vo;

class DateTime extends \DateTimeImmutable implements \JsonSerializable
{

    public function jsonSerialize(): string
    {
        return $this->format($this::ATOM);
    }
}