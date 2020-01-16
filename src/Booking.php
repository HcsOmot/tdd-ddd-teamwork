<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use Ramsey\Uuid\UuidInterface;

class Booking
{
    /** @var UuidInterface */
    private $bookingId;

    /** @var UuidInterface */
    private $meetingId;

    /** @var UuidInterface */
    private $venueId;

    public function __construct(UuidInterface $bookingId, UuidInterface $meetingId, UuidInterface $venueId)
    {
        $this->bookingId = $bookingId;
        $this->meetingId = $meetingId;
        $this->venueId = $venueId;
    }

    public function getId(): UuidInterface
    {
        return $this->bookingId;
    }

    public function getMeetingId(): UuidInterface
    {
        return $this->meetingId;
    }

    public function getVenueId(): UuidInterface
    {
        return $this->venueId;
    }
}
