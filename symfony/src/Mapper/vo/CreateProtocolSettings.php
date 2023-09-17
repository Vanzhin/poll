<?php

namespace App\Mapper\vo;

class CreateProtocolSettings implements \JsonSerializable
{
    public function __construct(
        private readonly string $template,
        private readonly bool   $ignoreFailedUsers,
        private readonly bool   $forEach,
    )
    {
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function isIgnoreFailedUsers(): bool
    {
        return $this->ignoreFailedUsers;
    }

    public function isForEach(): bool
    {
        return $this->forEach;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}