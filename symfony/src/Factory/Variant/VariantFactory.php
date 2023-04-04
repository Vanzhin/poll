<?php

namespace App\Factory\Variant;

class VariantFactory
{
    public function __construct()
    {
    }
    public function createBuilder(): VariantBuilder
    {
        return new VariantBuilder();
    }

}