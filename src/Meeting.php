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
    private $maxAttendeeCount;
    
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
    ) {
        $this->meetingId = $meetingId;
        $this->title = $title;
        $this->description = $description;
        $this->duration = $duration;
        $this->program = $program;
        $this->maxAttendeeCount = $maxAttendeeCount;
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
        
        if ($this->maxAttendeeCount >= $seatsRequired) {
            $this->registrations[] = $newRegistration;
            $this->maxAttendeeCount-=$seatsRequired;
            return;
        }

        throw new DomainException('Not enough seats available.');
    }
}
