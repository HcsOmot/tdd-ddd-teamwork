<?php

declare(strict_types=1);

namespace Procurios\Meeting\Meeting;

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

    public function rescheduleBy(DateInterval $offset): ProgramSlotDuration
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
}
