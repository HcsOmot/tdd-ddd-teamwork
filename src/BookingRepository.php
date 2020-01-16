<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use Ramsey\Uuid\UuidInterface;

interface BookingRepository
{
    public function getBooking(UuidInterface $id): Booking;

    public function save(Booking $booking): void;
}
