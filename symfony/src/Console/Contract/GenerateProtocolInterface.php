<?php

namespace App\Console\Contract;

use App\Entity\Protocol\Protocol;

interface GenerateProtocolInterface
{
    public function save(string $fileName): bool;

    public function generate(Protocol $protocol): string;

    public function generateMany(Protocol ...$protocols): array;

}