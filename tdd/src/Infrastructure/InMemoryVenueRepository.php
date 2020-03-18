<?php

declare(strict_types=1);

namespace App\Infrastructure;

use DomainException;
use App\Domain\Venue;
use Ramsey\Uuid\UuidInterface;

class InMemoryVenueRepository implements VenueRepository
{
    /** @var Venue[] */
    private $venues = [];

    public function getVenue(UuidInterface $id): Venue
    {
        if (false === \array_key_exists((string) $id, $this->venues)) {
            throw new DomainException('Venue not found');
        }

        return $this->venues[(string) $id];
    }

    public function save(Venue $venue): void
    {
        $this->venues[(string) $venue->getId()] = $venue;
    }
}
