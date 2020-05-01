<?php

declare(strict_types=1);

namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;

use Webmozart\Assert\Assert;
/** @ORM\Embeddable */
class Title
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

    public function __construct(string $title)
    {
        Assert::minLength($title, 5, 'Title must have at least five characters');
        $this->title = $title;
    }
}
