<?php

declare(strict_types=1);

namespace App\Application;

class RescheduleMeetingCommand
{
    /** @var string */
    private $meetingId;
    /** @var string */
    private $newStart;
    /** @var string */
    private $venueId;

    public function __construct(string $meetingId, string $newStart, string $venueId)
    {
        $this->meetingId = $meetingId;
        $this->newStart = $newStart;
        $this->venueId = $venueId;
    }

    public function getMeetingId(): string
    {
        return $this->meetingId;
    }

    public function getNewStart(): string
    {
        return $this->newStart;
    }

    public function getVenueId(): string
    {
        return $this->venueId;
    }
}
