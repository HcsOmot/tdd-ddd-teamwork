<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use Ramsey\Uuid\UuidInterface;

class Booking
{
    /**
     * @var UuidInterface
     */
    private $bookingId;
    /**
     * @var string
     */
    private $getMeetingId;
    /**
     * @var string
     */
    private $getVenueId;

    public function __construct(UuidInterface $bookingId, string $getMeetingId, string $getVenueId)
    {
        $this->bookingId = $bookingId;
        $this->getMeetingId = $getMeetingId;
        $this->getVenueId = $getVenueId;
    }

    public function getBookingId(): UuidInterface
    {
        return $this->bookingId;
    }

    public function getGetMeetingId(): string
    {
        return $this->getMeetingId;
    }

    public function getGetVenueId(): string
    {
        return $this->getVenueId;
    }
}
