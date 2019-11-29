<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DateInterval;
use DateTimeImmutable;
use DomainException;

class ProgramSlotDuration
{
    /** @var DateTimeImmutable */
    public $start;
    
    /** @var DateTimeImmutable */
    public $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        if ($start > $end) {
            throw new DomainException('Meeting cannot end before it started.');
        }
        
        $this->start = $start;
        $this->end = $end;
    }
}
