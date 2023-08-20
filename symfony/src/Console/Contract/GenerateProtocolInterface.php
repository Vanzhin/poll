<?php

namespace App\Console\Contract;

use App\Entity\Protocol;

interface GenerateProtocolInterface
{
    public function save(string $fileName): bool;

    public function generate(Protocol $protocol, string $template = null): string;

}