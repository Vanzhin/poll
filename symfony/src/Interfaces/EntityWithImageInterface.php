<?php

namespace App\Interfaces;

interface EntityWithImageInterface
{
    public function getImage(): ?string;

    public function setImage(?string $image): self;


}