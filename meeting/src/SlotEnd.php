<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DateTimeImmutable;

class SlotEnd
{
    /** @var DateTimeImmutable */
    private $endDate;

    public function __construct(DateTimeImmutable $endDate)
    {
        $this->endDate = $endDate;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

}
