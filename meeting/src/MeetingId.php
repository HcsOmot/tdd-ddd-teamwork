<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use Ramsey\Uuid\UuidInterface;

class MeetingId
{
    /** @var UuidInterface */
    private $uuid;

    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public function getId(): string
    {
        return $this->uuid->toString();
    }
}
