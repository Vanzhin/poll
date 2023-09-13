<?php

namespace App\Mapper\vo;

class MassCreateProtocol implements \JsonSerializable
{
    public function __construct(
        private readonly string                 $commissionId,
        private readonly string                 $groupId,
        private readonly string                 $orderNumber,
        private readonly \DateTimeImmutable     $orderDate,
        private readonly string                 $checkReason,
        private readonly string                 $number,
        private readonly string                 $testId,
        private readonly ?string                $regNumber,
        private readonly CreateProtocolSettings $settings

    )
    {
    }

    public function getCommissionId(): string
    {
        return $this->commissionId;
    }

    public function getGroupId(): string
    {
        return $this->groupId;
    }

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public function getOrderDate(): \DateTimeImmutable
    {
        return $this->orderDate;
    }

    public function getCheckReason(): string
    {
        return $this->checkReason;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getTestId(): string
    {
        return $this->testId;
    }

    public function getRegNumber(): ?string
    {
        return $this->regNumber;
    }

    public function getSettings(): CreateProtocolSettings
    {
        return $this->settings;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}