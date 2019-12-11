<?php

declare(strict_types=1);

namespace Procurios\Meeting;

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

    public function equals(EmailAddress $other): bool
    {
        return $this->emailAddress === $other->emailAddress;
    }
}
