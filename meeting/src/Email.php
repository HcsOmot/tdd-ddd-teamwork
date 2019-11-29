<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use Webmozart\Assert\Assert;

class Email
{
    /** @var string */
    private $email;

    public function __construct(string $email)
    {
        Assert::email($email);
        $this->email = $email;
    }
}
