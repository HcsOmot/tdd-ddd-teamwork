<?php
declare(strict_types=1);

namespace Procurios\Meeting;

use DateInterval;

final class ProgramSlot
{
    /** @var ProgramSlotDuration */
    private $duration;
    /** @var string */
    public $title;
    /** @var string */
    public $room;

    /**
     * @param string $title
     * @param string $room
     */
    public function __construct(ProgramSlotDuration $duration, string $title, string $room)
    {
        $this->duration = $duration;
        $this->title = $title;
        $this->room = $room;
    }

    public function rescheduledBy(DateInterval $dateInterval): ProgramSlot
    {
        $start = $this->duration->start->add($dateInterval);
        $end = $this->duration->end->add($dateInterval);

        $duration = new ProgramSlotDuration($start, $end);
        
        return new self($duration, $this->title, $this->room);
    }

    public function overlaps(ProgramSlot $other): bool
    {
        if ($this === $other) {
            return false;
        }
        
        if ($this->room !== $other->room){
            return false;
        }
        
        // pass the call for overlap to the duration itself
        if ($this->duration->start >= $other->duration->end) {
            return false;
        }

        if ($this->duration->end <= $other->duration->start) {
            return false;
        }

        return true;
    }
}
