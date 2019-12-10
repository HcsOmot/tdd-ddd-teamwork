<?php
declare(strict_types=1);

namespace Procurios\Meeting;

use DateInterval;
use Procurios\Meeting\Meeting\ProgramSlotDuration;

final class ProgramSlot
{
    /** @var string */
    private $title;

    /** @var string */
    private $room;

    /** @var ProgramSlotDuration */
    private $duration;

    public function __construct(ProgramSlotDuration $duration, string $title, string $room)
    {
        $this->title = $title;
        $this->room = $room;
        $this->duration = $duration;
    }

    public function rescheduleBy(DateInterval $offset): ProgramSlot
    {
        $rescheduledSlotDuration = $this->duration->rescheduleBy($offset);

        return new self($rescheduledSlotDuration,$this->title, $this->room);
    }

    public function getRoom(): string
    {
        return $this->room;
    }

    public function getDuration(): ProgramSlotDuration
    {
        return $this->duration;
    }

    public function before(ProgramSlot $that): bool
    {
        if ($this->duration->before($that->getDuration())) {
            return true;
        }

        return false;
    }

    public function after(ProgramSlot $that): bool
    {
        if ($this->duration->after($that->getDuration())) {
            return true;
        }

        return false;
    }

    public function overlapsWith(ProgramSlot $that): bool
    {
        if ($this->room !== $that->getRoom()) {
            return false;
        }

        if ($this === $that) {
            return false;
        }

        if ((false === $this->before($that)) && (false === $this->after($that))) {
            return true;
        }

        return false;
    }
}
