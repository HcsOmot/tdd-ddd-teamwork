<?php
declare(strict_types=1);

namespace Procurios\Meeting;

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
}
