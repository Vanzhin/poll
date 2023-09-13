<?php

namespace App\Mapper\vo;

use App\Entity\User\User;

class UserList
{
    private array $list;

    public function __construct(User...$list)
    {
        $this->list = $list;
    }

    public function getList(): array
    {
        return $this->list;
    }
}