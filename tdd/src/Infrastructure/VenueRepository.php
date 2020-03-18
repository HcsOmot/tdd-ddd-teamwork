<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\Venue;
use Ramsey\Uuid\UuidInterface;

interface VenueRepository
{
    public function getVenue(UuidInterface $venueId): Venue;

    public function save(Venue $venue): void;
}
