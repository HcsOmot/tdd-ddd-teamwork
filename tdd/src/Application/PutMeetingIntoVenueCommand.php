<?php

declare(strict_types=1);

namespace App\Application;

class PutMeetingIntoVenueCommand
{
    /** @var string */
    private $meetingId;
    /** @var string */
    private $venueId;

    public function __construct(string $meetingId, string $venueId)
    {
        $this->meetingId = $meetingId;
        $this->venueId = $venueId;
    }

    public function getMeetingId(): string
    {
        return $this->meetingId;
    }

    public function getVenueId(): string
    {
        return $this->venueId;
    }
}
