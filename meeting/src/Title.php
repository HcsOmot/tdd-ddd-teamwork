<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use Webmozart\Assert\Assert;

class Title
{
    /** @var string */
    private $title;

    public function __construct(string $title)
    {
        Assert::minLength($title, 5, 'Title must have at least five characters.');
        $this->title = $title;
    }
}
