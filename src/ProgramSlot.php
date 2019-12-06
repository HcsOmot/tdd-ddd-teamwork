<?php
declare(strict_types=1);

namespace Procurios\Meeting;

use DateTimeImmutable;
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

    public function rescheduleFor(DateTimeImmutable $newStart): ProgramSlot
    {
        $rescheduledSlotDuration = $this->duration->rescheduleFor($newStart);

        return new self($rescheduledSlotDuration,$this->title, $this->room);
    }
}
