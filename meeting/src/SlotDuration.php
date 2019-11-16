<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DateInterval;
use DateTimeImmutable;
use DomainException;
use Procurios\Meeting\test\SlotStartTest;

class SlotDuration
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
            throw new DomainException('Slot cannot end before it starts.');
        }

        $this->start = $start;
        $this->end = $end;
    }

    public function overlapsWith(SlotDuration $other): bool
    {
        return $this->start < $other->end;
    }

    public function rescheduledTo(DateTimeImmutable $newSlotStart): SlotDuration
    {
        $slotLasts = $this->start->diff($this->end);

        $newSlotEnd = $newSlotStart->add($slotLasts);

        return new self($newSlotStart, $newSlotEnd);
    }

    public function rescheduledBy(DateInterval $diff): SlotDuration
    {
        $newStart = $this->start->add($diff);

        $newEnd = $this->end->add($diff);

        return new self($newStart, $newEnd);
    }
}
