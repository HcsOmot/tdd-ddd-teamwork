<?php
declare(strict_types=1);

namespace Procurios\Meeting;

use DateTimeImmutable;
use DomainException;
use Ramsey\Uuid\UuidInterface;

final class Meeting
{
    /** @var UuidInterface */
    private $meetingId;
    /** @var Title */
    private $title;
    /** @var string */
    private $description;
    /** @var DateTimeImmutable */
    private $start;
    /** @var DateTimeImmutable */
    private $end;
    /** @var Program */
    private $program;

    public function __construct(
        UuidInterface $meetingId,
        Title $title,
        string $description,
        DateTimeImmutable $start,
        DateTimeImmutable $end,
        Program $program
    ) {
        $this->meetingId = $meetingId;
        $this->title = $title;
        $this->description = $description;
        $this->cannotEndBeforeItStarts($start, $end);
        $this->start = $start;
        $this->end = $end;
        $this->program = $program;
    }

    private function cannotEndBeforeItStarts(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        if ($end < $start) {
            throw new DomainException('Meeting cannot end before it has started');
        }
    }
}
