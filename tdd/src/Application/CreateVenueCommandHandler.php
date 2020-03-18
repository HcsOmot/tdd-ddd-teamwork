<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Venue;
use App\Infrastructure\VenueRepository;
use Ramsey\Uuid\Uuid;

class CreateVenueCommandHandler
{
    /** @var VenueRepository */
    private $venueRepository;

    public function __construct(VenueRepository $venueRepository)
    {
        $this->venueRepository = $venueRepository;
    }

    public function handle(CreateVenueCommand $createVenueCommand): void
    {
        $venue = new Venue(Uuid::fromString($createVenueCommand->getId()), $createVenueCommand->getName());

        $this->venueRepository->save($venue);
    }
}
