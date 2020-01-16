<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DomainException;
use Ramsey\Uuid\Uuid;

class BookVenueCommandHandler
{
    /** @var VenueRepository */
    private $venueRepository;

    /** @var BookingRepository */
    private $bookingRepository;

    public function __construct(
        VenueRepository $venueRepository,
        BookingRepository $bookingRepository
    ) {
        $this->venueRepository = $venueRepository;
        $this->bookingRepository = $bookingRepository;
    }

    public function __invoke(BookVenueCommand $command): void
    {
        /** @var Venue $venue */
        $venue = $this->venueRepository->getVenue(Uuid::fromString($command->getVenueId()));

//        QUESTION: is it enough to rely on the command itself? What guarantees do we have
//         that the meeting referenced in the command exists in the system?
//         Repository::get should throw an exception if the meeting is not found
//        /** @var Meeting $meeting */
//        $meeting = $this->bookingRepository->get(Uuid::fromString($command->getMeetingId()));

        if (false === $venue->availableBetween($command->getStart(), $command->getEnd())) {
            throw new DomainException('cant book sorry');
        }

        $bookingId = Uuid::uuid4();
        $booking = new Booking(
            $bookingId,
            Uuid::fromString($command->getMeetingId()),
            Uuid::fromString($command->getVenueId())
        );

        $venue->bookFor($command->getStart(), $command->getEnd());
//       TODO: Perhaps add the booking reference to the venue's booking? Does the venue need to know to whom the
//        booking  belongs to?
//        $venue->bookFor($command->getStart(), $command->getEnd(), $bookingId);
//       TODO: Booking alternative: don't check for availability, just book and handle the exception. ATM there's
//        nothing happening if the venue is already booked for this period

        $this->bookingRepository->save($booking);
    }
}
