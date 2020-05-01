<?php

declare(strict_types=1);

namespace App\Domain;

use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Embeddable */
class ProgramSlotDuration
{
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="start", nullable=false)
     */
    private $start;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="end", nullable=false)
     */
    private $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function rescheduleBy(DateInterval $offset): self
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

    public function before(self $that): bool
    {
        if ($this->end <= $that->getStart()) {
            return true;
        }

        return false;
    }

    public function after(self $that): bool
    {
        if ($this->start >= $that->getEnd()) {
            return true;
        }

        return false;
    }
}
