<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use DateTimeImmutable;
use DomainException;
use Ramsey\Uuid\UuidInterface;

class Venue
{
    /** @var UuidInterface */
    private $id;

    /** @var string */
    private $name;

    /** @var MeetingDuration[] */
    private $reservations;

    /** @var Meeting[] */
    private $bookedMeetings;

    public function __construct(UuidInterface $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->reservations = [];
        $this->bookedMeetings = [];
    }

    public function bookForMeeting(
        UuidInterface $meetingId,
        Title $title,
        string $description,
        MeetingDuration $duration,
        Program $program,
        int $maxAttendees
    ): void {
        $meeting = new Meeting($meetingId, $title, $description, $duration, $program, $maxAttendees);

        if (\in_array($duration, $this->reservations, true)) {
            throw new DomainException();
        }

        foreach ($this->reservations as $reservation) {
            if ($reservation->overlapsWith($duration)) {
                throw new DomainException();
            }
        }
        $this->reservations[(string) $meetingId] = $duration;
        $this->bookedMeetings[(string) $meetingId] = $meeting;
    }

    public function moveMeetingBooking(UuidInterface $meetingId, DateTimeImmutable $newStart): void
    {
        $meetingDuration = $this->reservations[(string) $meetingId];

        $scheduleOffset = $meetingDuration->calculateOffset($newStart);
        $targetReschedule = $meetingDuration->rescheduleBy($scheduleOffset);

        if (\in_array($targetReschedule, $this->reservations, true)) {
            throw new DomainException();
        }

        foreach ($this->reservations as $reservation) {
            if ($reservation->overlapsWith($targetReschedule)) {
                throw new DomainException();
            }
        }

        $this->reservations[(string) $meetingId] = $targetReschedule;
        $meeting = $this->bookedMeetings[(string) $meetingId];
        $meeting->rescheduleFor($newStart);
    }
}
