<?php

namespace App\Factory\Profile;

class ProfileFactory
{
    public function __construct()
    {
    }
    public function createBuilder(): ProfileBuilder
    {
        return new ProfileBuilder();
    }

}