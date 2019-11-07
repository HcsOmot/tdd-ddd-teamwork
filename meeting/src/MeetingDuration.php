<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DateTimeImmutable;
use DomainException;

class MeetingDuration
{
    /**
     * @var DateTimeImmutable
     */
    private $start;
    /**
     * @var DateTimeImmutable
     */
    private $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->validateDates($start, $end);
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): DateTimeImmutable
    {
        return $this->end;
    }

    /**
     * @param DateTimeImmutable $start
     * @param DateTimeImmutable $end
     */
    private function validateDates(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        if ($start > $end) {
            throw new DomainException('Meeting cannot start after it ends.');
        }

        $this->start = $start;
        $this->end = $end;
    }

}
