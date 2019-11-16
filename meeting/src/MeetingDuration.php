<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DateTimeImmutable;
use DomainException;

class MeetingDuration
{
    /**
     * @var DateTimeImmutable
     */
    private $start;
    /**
     * @var DateTimeImmutable
     */
    private $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->validateDates($start, $end);
    }

    public function from(): DateTimeImmutable
    {
        return $this->start;
    }

    public function until(): DateTimeImmutable
    {
        return $this->end;
    }

    /**
     * @param DateTimeImmutable $start
     * @param DateTimeImmutable $end
     */
    private function validateDates(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        if ($start > $end) {
            throw new DomainException('Meeting cannot end before it starts.');
        }

        $this->start = $start;
        $this->end = $end;
    }

    public function rescheduledTo(DateTimeImmutable $newMeetingStart): MeetingDuration
    {
        $meetingLasts = $this->start->diff($this->end);

        $newEnd = $this->end->add($meetingLasts);

        return new self($newMeetingStart, $newEnd);
    }
}
