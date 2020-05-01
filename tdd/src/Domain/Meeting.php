<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="meetings")
 */
final class Meeting
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $meetingId;

    /**
     * @var Title
     * @ORM\Column(type="string", nullable=false, length=50)
     */
    private $title;

    /** @ORM\Column(type="string", nullable=false, length=50) */
    private $description;

    /**
     * @var MeetingDuration
     * @ORM\Embedded(class="App\Domain\MeetingDuration", columnPrefix=false)
     */
    private $duration;

    /** @var Program
     * @ORM\OneToOne(targetEntity="App\Domain\Program",cascade={"PERSIST"})
     */
    private $program;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false, length=50)
     */
    private $availableSeats;

    /**
     * @var MeetingRegistration[]
     * @ORM\OneToMany(targetEntity="App\Domain\MeetingRegistration", mappedBy="id")
     */
    private $registrations = [];

    public function __construct(
        UuidInterface $meetingId,
        Title $title,
        string $description,
        MeetingDuration $duration,
        Program $program,
        int $maxAttendeeCount
    ) {
        $this->meetingId = $meetingId;
        $this->title = $title;
        $this->description = $description;
        $this->duration = $duration;
        $this->program = $program;
        $this->availableSeats = $maxAttendeeCount;
    }

    public function rescheduleFor(DateTimeImmutable $newStart): void
    {
        $startOffset = $this->duration->calculateOffset($newStart);
        $this->duration = $this->duration->rescheduleBy($startOffset);
        $this->program = $this->program->rescheduleFor($startOffset);
    }

    public function removePlusOne(UuidInterface $registrationId): void
    {
        $registration = $this->registrations[(string) $registrationId];

        $registration = $registration->removePlusOne();

        $this->registrations[(string) $registrationId] = $registration;

        ++$this->availableSeats;
    }

    public function removeRegistration(UuidInterface $registrationId): void
    {
        $seatsRequired = ($this->registrations[(string) $registrationId])->seatsRequired();
        $this->availableSeats += $seatsRequired;
    }

    public function addPlusOneAttendee(UuidInterface $registrationId, EmailAddress $attendee): void
    {
        $registration = $this->registrations[(string) $registrationId];
        $registration->addPlusOne($attendee);
    }

    public function register(
        UuidInterface $registrationId,
        EmailAddress $primaryAttendee,
        ?EmailAddress $plusOneAttendee
    ): void {
        $registration = new MeetingRegistration($registrationId, $primaryAttendee);
        if (null !== $plusOneAttendee) {
            $registration = $registration->addPlusOne($plusOneAttendee);
        }

        foreach ($this->registrations as $existingRegistration) {
            if ($existingRegistration->getEmail()->equals($registration->getEmail())) {
                throw new DomainException('User with this email already registered.');
            }
        }

        $seatsRequired = $registration->seatsRequired();

        if ($this->availableSeats >= $seatsRequired) {
            $this->registrations[(string) $registration->getId()] = $registration;
            $this->availableSeats -= $seatsRequired;

            return;
        }

        throw new DomainException('Not enough seats available.');
    }

    public function replacePlusOne(UuidInterface $registrationId, EmailAddress $newPlusOne): void
    {
        $registration = $this->registrations[(string) $registrationId];

        $primaryAttendee = $registration->getEmail();

        $this->register($registrationId, $primaryAttendee, $newPlusOne);
    }

    public function getId(): UuidInterface
    {
        return $this->meetingId;
    }

    public function overlapsWith(self $otherMeeting): bool
    {
        return $this->duration->overlapsWith($otherMeeting->duration);
    }

    public function getDuration(): MeetingDuration
    {
        return $this->duration;
    }
}
