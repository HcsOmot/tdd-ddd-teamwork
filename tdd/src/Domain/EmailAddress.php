<?php

declare(strict_types=1);

namespace App\Domain;

use Webmozart\Assert\Assert;

class EmailAddress
{
    /** @var string */
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
