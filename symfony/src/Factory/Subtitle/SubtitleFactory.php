<?php

namespace App\Factory\Subtitle;

use Doctrine\ORM\EntityManagerInterface;

class SubtitleFactory
{
    public function __construct()
    {
    }
    public function createBuilder(): SubtitleBuilder
    {
        return new SubtitleBuilder();
    }

}