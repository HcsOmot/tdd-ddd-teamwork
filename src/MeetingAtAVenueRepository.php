<?php

declare(strict_types=1);

namespace Procurios\Meeting;

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
