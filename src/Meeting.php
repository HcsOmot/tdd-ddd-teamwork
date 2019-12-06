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
    private $duration;

    /** @var Program */
    private $program;

    public function __construct(
        UuidInterface $meetingId,
        Title $title,
        string $description,
        MeetingDuration $duration,
        Program $program
    ) {
        $this->meetingId = $meetingId;
        $this->title = $title;
        $this->description = $description;
        $this->duration = $duration;
        $this->program = $program;
    }

}
