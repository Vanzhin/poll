<?php

namespace App\Entity\User\vo;

use Symfony\Component\Serializer\Annotation\Groups;

class Permissions implements \JsonSerializable
{
    public function __construct(
        private readonly bool $edit,
        private readonly bool $delete
    )
    {
    }

    /**
     * @return bool
     */
    #[Groups(['user_editable'])]
    public function isDelete(): bool
    {
        return $this->delete;
    }

    /**
     * @return bool
     */
    #[Groups(['user_editable'])]
    public function isEdit(): bool
    {
        return $this->edit;
    }
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}