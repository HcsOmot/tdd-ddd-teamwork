<?php

declare(strict_types=1);

namespace App\Domain;

use DateInterval;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="program_slots")
 */
final class ProgramSlot
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, length=50)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, length=50)
     */
    private $room;

    /**
     * @var ProgramSlotDuration
     * @ORM\Embedded(class="App\Domain\ProgramSlotDuration", columnPrefix=false)
     */
    private $duration;

    public function __construct(UuidInterface $id, ProgramSlotDuration $duration, string $title, string $room)
    {
        $this->title = $title;
        $this->room = $room;
        $this->duration = $duration;
        $this->id = $id;
    }

    public function rescheduleBy(DateInterval $offset): self
    {
        $rescheduledSlotDuration = $this->duration->rescheduleBy($offset);

        return new self($this->id, $rescheduledSlotDuration, $this->title, $this->room);
    }

    public function getRoom(): string
    {
        return $this->room;
    }

    public function getDuration(): ProgramSlotDuration
    {
        return $this->duration;
    }

    public function before(self $that): bool
    {
        if ($this->duration->before($that->getDuration())) {
            return true;
        }

        return false;
    }

    public function after(self $that): bool
    {
        if ($this->duration->after($that->getDuration())) {
            return true;
        }

        return false;
    }

    public function overlapsWith(self $that): bool
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
