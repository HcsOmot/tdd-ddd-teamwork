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

    /** @var DateTimeImmutable */
    private $bookedFrom;
    /** @var DateTimeImmutable */
    private $bookedUntil;

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

        if (false === $this->checkScheduleAvailability($duration)) {
            throw new DomainException('Venue already booked for this time period');
        }

        $this->reservations[(string) $meetingId] = $duration;
        $this->bookedMeetings[(string) $meetingId] = $meeting;
    }

    public function moveMeetingBooking(UuidInterface $meetingId, DateTimeImmutable $newStart): void
    {
        $meetingDuration = $this->reservations[(string) $meetingId];

        $scheduleOffset = $meetingDuration->calculateOffset($newStart);
        $newSchedule = $meetingDuration->rescheduleBy($scheduleOffset);

        if (false === $this->checkScheduleAvailability($newSchedule)) {
            throw new DomainException('Venue already booked for this time period');
        }

        $this->reservations[(string) $meetingId] = $newSchedule;
        $meeting = $this->bookedMeetings[(string) $meetingId];
        $meeting->rescheduleFor($newStart);
    }

    public function availableBetween(DateTimeImmutable $reservationFrom, DateTimeImmutable $reservationUntil): bool
    {
        if (null === $this->bookedFrom && null === $this->bookedUntil) {
            return true;
        }

        if ($this->bookedFrom <= $reservationFrom || $this->bookedFrom <= $reservationUntil) {
            return false;
        }

        if ($this->bookedUntil >= $reservationFrom || $this->bookedUntil >= $reservationUntil) {
            return false;
        }

        return true;
    }

    public function bookFor(DateTimeImmutable $reservationStart, DateTimeImmutable $reservationEnd): void
    {
        if ($this->availableBetween($reservationStart, $reservationEnd)) {
            $this->bookedFrom = $reservationStart;
            $this->bookedUntil = $reservationEnd;
        }
    }

    private function checkScheduleAvailability(MeetingDuration $newSchedule): bool
    {
        if (\in_array(
            $newSchedule,
            $this->reservations,
            true
        )
        ) {
            return false;
        }

        foreach ($this->reservations as $reservation) {
            if ($reservation->overlapsWith($newSchedule)) {
                return false;
            }
        }

        return true;
    }
}
