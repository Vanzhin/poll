<?php

namespace App\Repository\Shared\Filter\vo;

use DateTimeImmutable;

class DateTimeInterval implements \JsonSerializable
{
    private readonly ?DateTimeImmutable $from;
    private readonly ?DateTimeImmutable $to;

    public function __construct(?string $from, ?string $to, string $format = 'Y-m-d H:i:s')
    {
        $this->from = is_null($from) ? $from : DateTimeImmutable::createFromFormat($format, $from);
        $this->to = is_null($to) ? $to : DateTimeImmutable::createFromFormat($format, $to);
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getTo(): ?DateTimeImmutable
    {
        return $this->to;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getFrom(): ?DateTimeImmutable
    {
        return $this->from;
    }

    public function jsonSerialize(): array
    {
        return [
            "from" => $this->from?->format($format),
            "to" => $this->to?->format($format),
        ];
    }
}