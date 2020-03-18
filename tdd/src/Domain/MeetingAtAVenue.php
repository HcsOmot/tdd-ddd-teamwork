<?php

declare(strict_types=1);

namespace App\Domain;

use Ramsey\Uuid\UuidInterface;

class MeetingAtAVenue
{
    /** @var UuidInterface */
    private $meetingId;
    /** @var UuidInterface */
    private $venueId;

    public function __construct(UuidInterface $meetingId, UuidInterface $venueId)
    {
        $this->meetingId = $meetingId;
        $this->venueId = $venueId;
    }
}
