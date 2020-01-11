<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DateTimeImmutable;

class BookVenueCommand
{
    /**
     * @var string
     */
    private $venueId;
    /**
     * @var string
     */
    private $meetingId;
    /**
     * @var DateTimeImmutable
     */
    private $start;
    /**
     * @var DateTimeImmutable
     */
    private $end;

    public function __construct(string $venueId, string $meetingId, DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->venueId = $venueId;
        $this->meetingId = $meetingId;
        $this->start = $start;
        $this->end = $end;
    }

    public function getVenueId(): string
    {
        return $this->venueId;
    }

    public function getMeetingId(): string
    {
        return $this->meetingId;
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): DateTimeImmutable
    {
        return $this->end;
    }
}
