<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DomainException;
use Ramsey\Uuid\UuidInterface;

class MeetingRegistration
{
    /** @var UuidInterface */
    private $id;

    /** @var EmailAddress */
    private $attendee;
    
    /** @var EmailAddress */
    private $plusOne;

    public function __construct(UuidInterface $id, EmailAddress $attendee)
    {
        $this->id = $id;
        $this->attendee = $attendee;
    }

    public function addPlusOne(EmailAddress $otherAttendee): void
    {
        if (null === $this->plusOne) {
            $this->plusOne = $otherAttendee;
            return;
        }

        throw new DomainException('Cannot add more than 1 PlusOne attendees.');
    }

    public function getEmail(): EmailAddress
    {
        return $this->attendee;
    }

    public function seatsRequired(): int
    {
        return isset($this->plusOne) ? 2 : 1;
    }
}
