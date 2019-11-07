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
    /** @var Description */
    private $description;
    /** @var DateTimeImmutable */
    private $start;
    /** @var DateTimeImmutable */
    private $end;
    /** @var Program */
    private $program;

    /**
     * @param UuidInterface $meetingId
     * @param Title $title
     * @param Description $description
     * @param DateTimeImmutable $start
     * @param DateTimeImmutable $end
     * @param Program $program
     */
    public function __construct(
        UuidInterface $meetingId,
        Title $title,
        Description $description,
        DateTimeImmutable $start,
        DateTimeImmutable $end,
        Program $program
    ) {
        $this->meetingId = $meetingId;
        $this->title = $title;
        $this->description = $description;
        $this->validateDates($start, $end);
        $this->program = $program;
    }

    private function validateDates(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        if ($start > $end) {
            throw new DomainException('Meeting cannot start after it ends.');
        }

        $this->start = $start;
        $this->end = $end;
    }
}
