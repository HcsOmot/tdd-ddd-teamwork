<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use InvalidArgumentException;

class Description
{
    /** @var string */
    private $description;

    public function __construct(string $description)
    {
        if (empty($description)) {
            throw new InvalidArgumentException('Meeting description must not be empty.');
        }

        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
