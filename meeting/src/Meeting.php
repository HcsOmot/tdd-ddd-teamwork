<?php
declare(strict_types=1);

namespace Procurios\Meeting;

use DateInterval;
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
    private $meetingDuration;
    /** @var Program */
    private $program;
    /** @var null|int */
    private $attendeeLimit;
    /** @var array */
    private $registeredAttendees;

    /**
     * @param UuidInterface $meetingId
     * @param Title $title
     * @param string $description
     * @param MeetingDuration $meetingDuration
     * @param Program $program
     */
    public function __construct(
        UuidInterface $meetingId,
        Title $title,
        string $description,
        MeetingDuration $meetingDuration,
        Program $program,
        int $attendeeLimit = null
    ) {
        $this->meetingId = $meetingId;
        $this->title = $title;
        $this->description = $description;
        $this->meetingDuration = $meetingDuration;
        $this->program = $program;
        $this->attendeeLimit = $attendeeLimit;
        $this->registeredAttendees = [];
    }

    public function rescheduleBy(DateInterval $dateInterval): Meeting
    {
        $rescheduledDuration = $this->meetingDuration->rescheduleBy($dateInterval);

        $rescheduledPrograms = $this->program->rescheduleBy($dateInterval);

        return new self(
          $this->meetingId,
          $this->title,
          $this->description,
          $rescheduledDuration,
          $rescheduledPrograms,
          $this->attendeeLimit
        );
    }

    public function registerAttendee(Email $email): self
    {
        if ($this->attendeeLimit === 0) {
            throw new DomainException('Registrations for this meeting are closed.');
        }
        
        Assert::false(
            in_array($email, $this->registeredAttendees),
            'This attendee already registered for this meeting.'
        );
        $this->registeredAttendees[] = $email;
        $this->attendeeLimit--;
        
        $meeting = new self(
            $this->meetingId,
            $this->title,
            $this->description,
            $this->meetingDuration,
            $this->program,
            $this->attendeeLimit
        );
//        @TODO: entities are mutable, VO are not - no need to return new instance here
        $meeting->registeredAttendees = $this->registeredAttendees;
        
        return $meeting;
    }

    public function getAttendees(): array
    {
        return $this->registeredAttendees;
    }
}
