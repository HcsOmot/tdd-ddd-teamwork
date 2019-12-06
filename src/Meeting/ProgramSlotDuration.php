<?php

declare(strict_types=1);

namespace Procurios\Meeting\Meeting;

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

    public function rescheduleFor(DateTimeImmutable $newStart): ProgramSlotDuration
    {
        $startOffset = $this->start->diff($newStart);
        $rescheduledStart = $this->start->add($startOffset);
        $slotDuration = $this->start->diff($this->end);
        $rescheduledEnd = $this->end->add($startOffset)->add($slotDuration);

        return new self($rescheduledStart, $rescheduledEnd);
    }
}
