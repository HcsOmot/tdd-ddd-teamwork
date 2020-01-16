<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use Ramsey\Uuid\UuidInterface;

interface VenueRepository
{
    public function getVenue(UuidInterface $venueId): Venue;

    public function save(Venue $venue): void;
}
