<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DateTimeImmutable;

class MeetingStart
{
    /** @var DateTimeImmutable */
    private $startDate;

    public function __construct(DateTimeImmutable $startDate)
    {
        $this->startDate = $startDate;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }
}
