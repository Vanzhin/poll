<?php

namespace App\Entity\Protocol\vo;

use Symfony\Component\Serializer\Annotation\Groups;

class ProtocolSettings implements \JsonSerializable
{
    public function __construct(
        private readonly string $template ,
        private readonly bool $ignore_failed_users,
        private readonly bool $for_each,

    )
    {
    }
    #[Groups(['admin_protocol'])]
    public function getTemplate(): string
    {
        return $this->template;
    }
    #[Groups(['admin_protocol'])]
    public function isIgnoreFailedUsers(): bool
    {
        return $this->ignore_failed_users;
    }
    #[Groups(['admin_protocol'])]
    public function isForEach(): bool
    {
        return $this->for_each;
    }

    public function __toString(): string
    {
       return json_encode($this->jsonSerialize(), true);
    }

    public function jsonSerialize(): array
    {
       return get_object_vars($this);
    }
}