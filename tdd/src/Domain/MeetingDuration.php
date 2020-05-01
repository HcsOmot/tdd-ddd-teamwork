<?php

declare(strict_types=1);

namespace App\Domain;

use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/** @ORM\Embeddable */
class MeetingDuration
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
        if ($end < $start) {
            throw new DomainException('Meeting cannot end before it has started');
        }

        $this->start = $start;
        $this->end = $end;
    }

    public function rescheduleBy(DateInterval $offset): self
    {
        $newStart = $this->start->add($offset);
        $newEnd = $this->end->add($offset);

        return new self($newStart, $newEnd);
    }

    public function calculateOffset(DateTimeImmutable $newStart): DateInterval
    {
        return $this->start->diff($newStart);
    }

    public function overlapsWith(self $that): bool
    {
        if ($this === $that) {
            return true;
        }

        if ((false === $this->before($that)) && (false === $this->after($that))) {
            return true;
        }

        return false;
    }

    private function before(self $that): bool
    {
        return $this->end <= $that->start;
    }

    private function after(self $that): bool
    {
        return $this->start >= $that->end;
    }
}
