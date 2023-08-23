<?php

namespace App\Repository\Group\Filter\vo;

use DateTimeZone;

class DateTime extends \DateTimeImmutable implements \JsonSerializable
{
    public function __construct(string $datetime = "now", ?DateTimeZone $timezone = null)
    {
        parent::__construct($datetime, $timezone);
    }

    public function jsonSerialize(): string
    {
        return $this->format('Y-m-d H:i:s');
    }
}