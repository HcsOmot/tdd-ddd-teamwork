<?php
declare(strict_types=1);

namespace Procurios\Meeting;

use DateInterval;

final class ProgramSlot
{
    /** @var SlotDuration */
    private $slotDuration;
    /** @var string */
    private $title;
    /** @var string */
    private $room;

    /**
     * @param SlotDuration $slotDuration
     * @param string $title
     * @param string $room
     */
    public function __construct(SlotDuration $slotDuration, string $title, string $room)
    {
        $this->slotDuration = $slotDuration;
        $this->title = $title;
        $this->room = $room;
    }

    public function overlapsWith(ProgramSlot $other): bool
    {
        return $this->slotDuration->overlapsWith($other->slotDuration) && $this->room === $other->room;
    }

    public function rescheduledTo(\DateTimeImmutable $newProgramStart): ProgramSlot
    {
        $slotStartDiff = ($this->slotDuration->from())->diff($newProgramStart);

        $newSlotStart = ($this->slotDuration->from())->add($slotStartDiff);

        $rescheduledProgramSlotDuration = $this->slotDuration->rescheduledTo($newSlotStart);

        return new self($rescheduledProgramSlotDuration, $this->title, $this->room);
    }

    public function rescheduledBy(DateInterval $diff): ProgramSlot
    {
        $rescheduledSlotDuration = $this->slotDuration->rescheduledBy($diff);
        return new self($rescheduledSlotDuration, $this->title, $this->room);
    }
}
