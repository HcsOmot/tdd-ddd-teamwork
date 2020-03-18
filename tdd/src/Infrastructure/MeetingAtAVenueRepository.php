<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\MeetingAtAVenue;

class MeetingAtAVenueRepository
{
    /** @var array */
    private $records = [];

    public function save(MeetingAtAVenue $meetingInAVenue): void
    {
        $this->records[] = $meetingInAVenue;
    }

    public function has(MeetingAtAVenue $newMeetingAtAVenue): bool
    {
        return \in_array($newMeetingAtAVenue, $this->records, false);
    }
}
