<?php

declare(strict_types=1);

namespace Procurios\Meeting\Tests;

use DateTimeImmutable;
use Procurios\Meeting\Meeting;
use Procurios\Meeting\MeetingAndSpecification;
use Procurios\Meeting\MeetingAtAVenueRepository;
use Procurios\Meeting\MeetingAtVenueSpecification;
use Procurios\Meeting\MeetingDuringSpecification;
use Procurios\Meeting\MeetingOverlapException;
use Procurios\Meeting\MeetingRepository;
use Procurios\Meeting\Venue;
use Procurios\Meeting\VenueRepository;
use Ramsey\Uuid\Uuid;

class RescheduleMeetingCommandHandler
{
    private $meetingRepository;
    private $venueRepository;
    private $meetingAtAVenueRepository;

    public function __construct(
        MeetingRepository $meetingRepository,
        VenueRepository $venueRepository,
        MeetingAtAVenueRepository $meetingAtAVenueRepository
    )
    {
        $this->meetingRepository = $meetingRepository;
        $this->venueRepository = $venueRepository;
        $this->meetingAtAVenueRepository = $meetingAtAVenueRepository;
    }

    public function __invoke(RescheduleMeetingCommand $command): void
    {
//        OLD CODE
//        $meeting = $this->meetingRepository->getMeeting(Uuid::fromString($command->getMeetingId()));
//
//        $meeting->rescheduleFor(DateTimeImmutable::createFromFormat('Y-m-d:H:i', $command->getNewStart()));
//
//        $this->meetingRepository->save($meeting);
//        SPIKING:
//        1. we have: meeting, venue, new desired start time
//        2. reschedule: no overlap in the same venue:
//        3. get all meetings for desired venue that have duration overlap
//        4. if there are none, it's ok to reschedule
//        NEW CODE:
        [$meeting, $venue] = $this->verifyObjectsExist($command);
        $this->verifyNoMeetingOverlap($command, $meeting, $venue);
        $this->rescheduleMeeting($command, $meeting);
    }

    private function verifyObjectsExist(RescheduleMeetingCommand $command): array
    {
        $meeting = $this->meetingRepository->getMeeting(Uuid::fromString($command->getMeetingId()));
        $venue = $this->venueRepository->getVenue(Uuid::fromString($command->getVenueId()));
        return array($meeting, $venue);
}

    private function verifyNoMeetingOverlap(RescheduleMeetingCommand $command, Meeting $meeting, Venue $venue): void
    {
        $newDurationOffset = $meeting->getDuration()
            ->calculateOffset(
                DateTimeImmutable::createFromFormat(
                    'Y-m-d:H:i',
                    $command->getNewStart()
                )
            );

        $newMeetingDuration = $meeting->getDuration()->rescheduleBy($newDurationOffset);

        $meetingSpec = new MeetingAndSpecification(
            [
                new MeetingDuringSpecification($newMeetingDuration),
                new MeetingAtVenueSpecification(
                    $venue->getId(),
                    $this->meetingAtAVenueRepository),
            ]
        );

        $meetings = $this->meetingRepository->findBySpec($meetingSpec);

        if (0 !== count($meetings)) {
            throw new MeetingOverlapException();
        }
    }

    private function rescheduleMeeting(RescheduleMeetingCommand $command, Meeting $meeting): void
    {
        $meeting->rescheduleFor(
            DateTimeImmutable::createFromFormat(
                'Y-m-d:H:i',
                $command->getNewStart()
            )
        );
    }
}
