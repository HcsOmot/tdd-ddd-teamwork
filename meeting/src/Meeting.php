<?php
declare(strict_types=1);

namespace Procurios\Meeting;

final class Meeting
{
    /** @var MeetingId */
    private $meetingId;
    /** @var Title */
    private $title;
    /** @var Description */
    private $description;
    /** @var MeetingDuration */
    private $duration;
    /** @var Program */
    private $program;

    /**
     * @param MeetingId $meetingId
     * @param Title $title
     * @param Description $description
     * @param MeetingDuration $duration
     * @param Program $program
     */
    public function __construct(
        MeetingId $meetingId,
        Title $title,
        Description $description,
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
