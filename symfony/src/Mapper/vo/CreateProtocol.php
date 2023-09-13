<?php

declare(strict_types=1);

namespace App\Mapper\vo;

class CreateProtocol implements \JsonSerializable
{
    public function __construct(
        private readonly string             $commissionId,
        private readonly string             $groupId,
        private readonly string             $orderNumber,
        private readonly \DateTimeImmutable $orderDate,
        private readonly string             $checkReason,
        private readonly string             $number,
        private readonly string             $testId,
        private readonly ?string            $regNumber,
        private readonly string             $template,
        private readonly UserList           $users

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

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getUsers(): UserList
    {
        return $this->users;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}