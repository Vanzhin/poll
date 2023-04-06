<?php

namespace App\Enum;

enum Format: string
{
    case xml = 'xml';
    case json = 'json';


    public static function fromName(string $name): ?string
    {
        $reflection = new \ReflectionEnum(self::class);
        if ($reflection->hasConstant($name)) {
            return constant("self::$name")->value;
        }
        return null;

    }
}
