<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Meeting;
use App\Domain\MeetingDuration;

class MeetingDuringSpecification implements MeetingSpecification
{
    /** @var MeetingDuration */
    private $meetingDuration;

    /**
     * MeetingDuringSpecification constructor.
     *
     * @param $meetingDuration
     */
    public function __construct(MeetingDuration $meetingDuration)
    {
        $this->meetingDuration = $meetingDuration;
    }

    public function isSatisfiedBy(Meeting $meeting): bool
    {
        if ($meeting->getDuration()->overlapsWith($this->meetingDuration)) {
            return true;
        }

        return false;
    }
}
