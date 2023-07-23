<?php

namespace App\Message;

use App\Entity\User\User;

class EmailNotification
{
    public function __construct(private readonly User $user)
    {
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}