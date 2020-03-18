<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\MeetingAtAVenue;
use App\Domain\MeetingOverlapException;
use App\Infrastructure\MeetingAtAVenueRepository;
use App\Infrastructure\MeetingRepository;
use App\Infrastructure\VenueRepository;
use Ramsey\Uuid\Uuid;

class PutMeetingIntoVenueCommandHandler
{
    /** @var MeetingRepository */
    private $meetingRepository;
    /** @var VenueRepository */
    private $venueRepository;
    /** @var MeetingAtAVenueRepository */
    private $meetingAtAVenueRepository;

    public function __construct(
        MeetingRepository $meetingRepository,
        VenueRepository $venueRepository,
        MeetingAtAVenueRepository $meetingAtAVenueRepository
    ) {
        $this->meetingRepository = $meetingRepository;
        $this->venueRepository = $venueRepository;
        $this->meetingAtAVenueRepository = $meetingAtAVenueRepository;
    }

    public function handle(PutMeetingIntoVenueCommand $command): void
    {
        [$meeting, $venue] = $this->verifyObjectsExist($command);
        $this->verifyNoMeetingOverlap($meeting, $venue);
        $this->saveMeetingInVenue($meeting, $venue);
    }

    private function verifyObjectsExist(PutMeetingIntoVenueCommand $command): array
    {
        $meeting = $this->meetingRepository->getMeeting(Uuid::fromString($command->getMeetingId()));
        $venue = $this->venueRepository->getVenue(Uuid::fromString($command->getVenueId()));

        return [$meeting, $venue];
    }

    private function verifyNoMeetingOverlap($meeting, $venue): void
    {
        $spec = new MeetingAndSpecification(
            [
                new MeetingDuringSpecification($meeting->getDuration()),
                new MeetingAtVenueSpecification($venue->getId(), $this->meetingAtAVenueRepository),
            ]
        );
        $meetings = $this->meetingRepository->findBySpec($spec);
        if (0 !== \count($meetings)) {
            throw new MeetingOverlapException();
        }
    }

    private function saveMeetingInVenue($meeting, $venue): void
    {
        $meetingInAVenue = new MeetingAtAVenue($meeting->getId(), $venue->getId());
        $this->meetingAtAVenueRepository->save($meetingInAVenue);
    }
}

// meetings and venues should know as little about each other as possible
