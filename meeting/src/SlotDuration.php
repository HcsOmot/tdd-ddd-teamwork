<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DomainException;

class SlotDuration
{
    /**
     * @var SlotStart
     */
    private $start;
    /**
     * @var SlotEnd
     */
    private $end;

    public function __construct(SlotStart $start, SlotEnd $end)
    {
        $this->validateDates($start, $end);
    }

    public function from(): SlotStart
    {
        return $this->start;
    }

    public function until(): SlotEnd
    {
        return $this->end;
    }

    /**
     * @param SlotStart $start
     * @param SlotEnd $end
     */
    private function validateDates(SlotStart $start, SlotEnd $end)
    {
        if ($start->getStartDate() > $end->getEndDate()) {
            throw new DomainException('Slot cannot end before it starts.');
        }

        $this->start = $start;
        $this->end = $end;
    }

    public function overlapsWith(SlotDuration $other): bool
    {
        return $this->start->getStartDate() < $other->end->getEndDate();
    }

}
