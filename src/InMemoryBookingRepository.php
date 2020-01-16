<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DomainException;
use Ramsey\Uuid\UuidInterface;

class InMemoryBookingRepository implements BookingRepository
{
    /** @var Booking[] */
    private $bookings = [];

    public function getBooking(UuidInterface $id): Booking
    {
        if (false === \array_key_exists((string) $id, $this->bookings)) {
            throw new DomainException('Booking not found');
        }

        return $this->bookings[(string) $id];
    }

    public function save(Booking $booking): void
    {
        $this->bookings[(string) $booking->getId()] = $booking;
    }
}
