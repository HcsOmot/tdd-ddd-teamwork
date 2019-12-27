<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DateInterval;
use DateTimeImmutable;
use DomainException;

class MeetingDuration
{
    /** @var DateTimeImmutable */
    private $start;

    /** @var DateTimeImmutable */
    private $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        if ($end < $start) {
            throw new DomainException('Meeting cannot end before it has started');
        }

        $this->start = $start;
        $this->end = $end;
    }

    public function rescheduleBy(DateInterval $offset): self
    {
        $newStart = $this->start->add($offset);
        $newEnd = $this->end->add($offset);

        return new self($newStart, $newEnd);
    }

    public function calculateOffset(DateTimeImmutable $newStart): DateInterval
    {
        return $this->start->diff($newStart);
    }
}
