<?php

namespace App\Models\Position;

class Position
{
    public $key;

    public $name;

    public $style;

    public function __construct(string $key, string $name, array $style)
    {
        $this->key = $key;
        $this->name = $name;
        $this->style = $style;
    }
}
