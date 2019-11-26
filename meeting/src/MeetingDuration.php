<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DateInterval;
use DateTimeImmutable;
use DomainException;

class MeetingDuration
{
    /** @var DateTimeImmutable */
    private $start;
    
    /** @var DateTimeImmutable */
    private $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        if ($start > $end) {
            throw new DomainException('Meeting cannot end before it started.');
        }
        
        $this->start = $start;
        $this->end = $end;
    }

    public function rescheduleBy(DateInterval $dateInterval): MeetingDuration
    {
        $start = $this->start->add($dateInterval);
        $end = $this->end->add($dateInterval);

        return new self(
            $start,
            $end
        );
    }
}
