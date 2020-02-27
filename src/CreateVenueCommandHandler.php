<?php

declare(strict_types=1);

namespace Procurios\Meeting;

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
