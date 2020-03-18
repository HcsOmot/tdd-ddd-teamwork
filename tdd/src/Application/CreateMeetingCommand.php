<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\MeetingDuration;
use App\Domain\Program;
use App\Domain\Title;
use Ramsey\Uuid\UuidInterface;

class CreateMeetingCommand
{
    /**
     * @var UuidInterface
     */
    private $meetingId;
    /**
     * @var Title
     */
    private $title;
    /**
     * @var string
     */
    private $description;
    /**
     * @var MeetingDuration
     */
    private $duration;
    /**
     * @var Program
     */
    private $program;
    /**
     * @var int
     */
    private $maxAttendees;

    public function __construct(
        UuidInterface $meetingId,
        Title $title,
        string $description,
        MeetingDuration $duration,
        Program $program,
        int $maxAttendees
    ) {
        $this->meetingId = $meetingId;
        $this->title = $title;
        $this->description = $description;
        $this->duration = $duration;
        $this->program = $program;
        $this->maxAttendees = $maxAttendees;
    }

    public function getMeetingId(): UuidInterface
    {
        return $this->meetingId;
    }

    public function getTitle(): Title
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDuration(): MeetingDuration
    {
        return $this->duration;
    }

    public function getProgram(): Program
    {
        return $this->program;
    }

    public function getMaxAttendees(): int
    {
        return $this->maxAttendees;
    }
}
