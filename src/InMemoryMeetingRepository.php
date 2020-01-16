<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DomainException;
use Ramsey\Uuid\UuidInterface;

class InMemoryMeetingRepository implements MeetingRepository
{
    /** @var Meeting[] */
    private $meetings = [];

    public function getMeeting(UuidInterface $id): Meeting
    {
        if (false === \array_key_exists((string) $id, $this->meetings)) {
            throw new DomainException('Meeting not found');
        }

        return $this->meetings[(string) $id];
    }

    public function save(Meeting $meeting): void
    {
        $this->meetings[(string) $meeting->getId()] = $meeting;
    }
}
