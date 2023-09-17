<?php

namespace App\Repository\Test\Filter;

class UserTest implements \JsonSerializable
{

    private array $users;


    public function __construct(
//        private readonly \DateTimeImmutable $from,
//        private readonly \DateTimeImmutable $to,
        string                              ...$users
    )
    {
        $this->users = $users;
    }

    public function getUsers(): array
    {
        return $this->users;
    }

//    public function getFrom(): \DateTimeImmutable
//    {
//        return $this->from;
//    }
//
//    public function getTo(): \DateTimeImmutable
//    {
//        return $this->to;
//    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);

    }
}