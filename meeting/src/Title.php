<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use InvalidArgumentException;

class Title
{
    /** @var string */
    private $title;

    public function __construct(string $title)
    {
        if (strlen($title) < 5) {
            throw new InvalidArgumentException('Meeting title must be at least 5 characters long');
        }

        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
