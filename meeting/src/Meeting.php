<?php
declare(strict_types=1);

namespace Procurios\Meeting;

use DateTimeImmutable;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;

final class Meeting
{
    /** @var UuidInterface */
    private $meetingId;
    /** @var string */
    private $title;
    /** @var string */
    private $description;
    /** @var DateTimeImmutable */
    private $start;
    /** @var DateTimeImmutable */
    private $end;
    /** @var Program */
    private $program;

    /**
     * @param UuidInterface $meetingId
     * @param string $title
     * @param string $description
     * @param DateTimeImmutable $start
     * @param DateTimeImmutable $end
     * @param Program $program
     */
    public function __construct(
        UuidInterface $meetingId,
        string $title,
        string $description,
        DateTimeImmutable $start,
        DateTimeImmutable $end,
        Program $program
    ) {
        $this->meetingId = $meetingId;
        $this->validateTitle($title);
        $this->description = $description;
        $this->start = $start;
        $this->end = $end;
        $this->program = $program;
    }

    private function validateTitle(string $title)
    {
        if (strlen($title) < 5) {
            throw new InvalidArgumentException('Meeting title must be at least 5 characters long');
        }

        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
