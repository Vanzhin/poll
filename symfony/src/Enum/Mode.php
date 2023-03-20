<?php

namespace App\Enum;

enum Mode: string
{
    case rnd = 'Случайный набор';
    case rnd20 = 'Случайный набор из 20 вопросов';
    case rnd20t = 'Случайный набор из 20 вопросов (таймер)';

    public static function fromName(string $name): ?string
    {
        $reflection = new \ReflectionEnum(self::class);
        if ($reflection->hasConstant($name)) {
            return constant("self::$name")->value;
        }
        return null;

    }

}
