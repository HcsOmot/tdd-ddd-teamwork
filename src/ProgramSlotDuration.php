<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DateInterval;
use DateTimeImmutable;

class ProgramSlotDuration
{
    /** @var DateTimeImmutable */
    private $start;

    /** @var DateTimeImmutable */
    private $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function rescheduleBy(DateInterval $offset): self
    {
        $rescheduledStart = $this->start->add($offset);
        $rescheduledEnd = $this->end->add($offset);

        return new self($rescheduledStart, $rescheduledEnd);
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): DateTimeImmutable
    {
        return $this->end;
    }

    public function before(self $that): bool
    {
        if ($this->end <= $that->getStart()) {
            return true;
        }

        return false;
    }

    public function after(self $that): bool
    {
        if ($this->start >= $that->getEnd()) {
            return true;
        }

        return false;
    }
}
