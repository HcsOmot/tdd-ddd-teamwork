<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DomainException;
use Ramsey\Uuid\Uuid;

class BookVenueCommandHandler
{
    /**
     * @var VenueRepository
     */
    private $venueRepository;
    /**
     * @var MeetingRepository
     */
    private $meetingRepository;

    public function __construct(VenueRepository $venueRepository, MeetingRepository $meetingRepository)
    {
        $this->venueRepository = $venueRepository;
        $this->meetingRepository = $meetingRepository;
    }

    public function __invoke(BookVenueCommand $command): void
    {
        /** @var Venue $venue */
        $venue = $this->venueRepository->get($command->getVenueId());
        $meeting = $this->meetingRepository->get($command->getMeetingId());

        if (false === $venue->availableBetween($command->getStart(), $command->getEnd())) {
            throw new DomainException('cant book sorry');
        }

        $bookingId = Uuid::uuid4();
        $booking = new Booking($bookingId, $command->getMeetingId(), $command->getVenueId());

        $venue->bookFor($command->getStart(), $command->getEnd());
//        Perhaps add the booking reference to the venue's booking? Does the venue need to know to whom the booking
// belongs to?
//        $venue->bookFor($command->getStart(), $command->getEnd(), $bookingId);
    }
}
