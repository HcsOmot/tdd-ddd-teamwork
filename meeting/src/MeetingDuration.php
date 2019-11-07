<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DomainException;

class MeetingDuration
{
    /**
     * @var MeetingStart
     */
    private $start;
    /**
     * @var MeetingEnd
     */
    private $end;

    public function __construct(MeetingStart $start, MeetingEnd $end)
    {
        $this->validateDates($start, $end);
    }

    public function from(): MeetingStart
    {
        return $this->start;
    }

    public function until(): MeetingEnd
    {
        return $this->end;
    }

    /**
     * @param MeetingStart $start
     * @param MeetingEnd $end
     */
    private function validateDates(MeetingStart $start, MeetingEnd $end)
    {
        if ($start->getStartDate() > $end->getEndDate()) {
            throw new DomainException('Meeting cannot start after it ends.');
        }

        $this->start = $start;
        $this->end = $end;
    }

}
