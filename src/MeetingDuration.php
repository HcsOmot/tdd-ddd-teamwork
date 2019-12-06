<?php

declare(strict_types=1);

namespace Procurios\Meeting;

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
        if ($end < $start) {
            throw new DomainException('Meeting cannot end before it has started');
        }
        
        $this->start = $start;
        $this->end = $end;
    }
}
