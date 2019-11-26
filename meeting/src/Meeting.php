<?php
declare(strict_types=1);

namespace Procurios\Meeting;

use Ramsey\Uuid\UuidInterface;

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
        Program $program
    ) {
        $this->meetingId = $meetingId;
        $this->title = $title;
        $this->description = $description;
        $this->meetingDuration = $meetingDuration;
        $this->program = $program;
    }
}
