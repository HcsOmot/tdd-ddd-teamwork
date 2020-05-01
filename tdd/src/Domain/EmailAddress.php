<?php

declare(strict_types=1);

namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/** @ORM\Embeddable */
class EmailAddress
{
    /**
     * @var string
     * @ORM\Column(type="string", name="email_address", nullable=false)
     */
    private $emailAddress;

    public function __construct(string $emailAddress)
    {
        Assert::email($emailAddress);

        $this->emailAddress = $emailAddress;
    }

    public function equals(self $other): bool
    {
        return $this->emailAddress === $other->emailAddress;
    }
}
