<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Meeting;
use App\Domain\MeetingAtAVenue;
use App\Infrastructure\MeetingAtAVenueRepository;
use Ramsey\Uuid\UuidInterface;

class MeetingAtVenueSpecification implements MeetingSpecification
{
    /** @var UuidInterface */
    private $venueId;
    /** @var MeetingAtAVenueRepository */
    private $repository;

    public function __construct(UuidInterface $venueId, MeetingAtAVenueRepository $repository)
    {
        $this->venueId = $venueId;
        $this->repository = $repository;
    }

    public function isSatisfiedBy(Meeting $meeting): bool
    {
        $newMeetingAtAVenue = new MeetingAtAVenue($meeting->getId(), $this->venueId);

        return $this->repository->has($newMeetingAtAVenue);
    }
}

// specs tackle cross concern operations