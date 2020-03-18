<?php

declare(strict_types=1);

namespace App\Domain;

class Room
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(): void
    {
    }
}
