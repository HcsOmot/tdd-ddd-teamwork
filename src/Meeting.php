<?php
declare(strict_types=1);

namespace Procurios\Meeting;

use DateTimeImmutable;
use DomainException;
use Ramsey\Uuid\UuidInterface;
use Webmozart\Assert\Assert;

final class Meeting
{
    /** @var UuidInterface */
    private $meetingId;

    /** @var Title */
    private $title;

    /** @var string */
    private $description;

    /** @var MeetingDuration */
    private $duration;

    /** @var Program */
    private $program;

    /**
     * @var int
     */
    private $availableSeats;

    /**
     * @var MeetingRegistration[]
     */
    private $registrations = [];

    public function __construct(
        UuidInterface $meetingId,
        Title $title,
        string $description,
        MeetingDuration $duration,
        Program $program,
        int $maxAttendeeCount
    )
    {
        $this->meetingId = $meetingId;
        $this->title = $title;
        $this->description = $description;
        $this->duration = $duration;
        $this->program = $program;
        $this->availableSeats = $maxAttendeeCount;
    }

    public function rescheduleFor(DateTimeImmutable $newStart)
    {
        $startOffset = $this->duration->calculateOffset($newStart);
        $this->duration = $this->duration->rescheduleBy($startOffset);
        $this->program = $this->program->rescheduleFor($startOffset);
    }

    public function register(MeetingRegistration $newRegistration): void
    {
        foreach ($this->registrations as $existingRegistration) {
            if ($existingRegistration->getEmail()->equals($newRegistration->getEmail())) {
                throw new DomainException('User with this email already registered.');
            }
        }

        $seatsRequired = $newRegistration->seatsRequired();

        if ($this->availableSeats >= $seatsRequired) {
            $this->registrations[(string)$newRegistration->getId()] = $newRegistration;
            $this->availableSeats -= $seatsRequired;
            return;
        }

        throw new DomainException('Not enough seats available.');
    }

    public function updateRegistration(MeetingRegistration $registration): void
    {
        $seatsRequired = $registration->seatsRequired();
        $originalSeatsRequired = $this->registrations[(string)$registration->getId()]->seatsRequired();

        $delta = $originalSeatsRequired - $seatsRequired;

        if ($this->availableSeats + $delta >= 0) {
            $this->registrations[(string)$registration->getId()] = $registration;
            $this->availableSeats = +$delta;
            return;
        }

        throw new DomainException('Not enough seats available.');
    }

    // TODO: rules and behaviour belong together - in a consistent location - removing and adding plus1 from
    //inside of meetingRegistration object separates the rule (can't overbook - stated in the Meeting) from the
// behaviour (add/remove plus1 - stated in the meetingRegistration) 
    public function removePlusOne(UuidInterface $registrationId): void
    {
//        $registration = $this->registrations[(string) $registrationId];
//        
//        $registration = $registration->removePlusOne();
//        
//        $this->registrations[(string) $registrationId] = $registration;
        
        $this->availableSeats++;
    }
}
