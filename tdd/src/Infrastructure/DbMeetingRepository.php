<?php

declare(strict_types=1);

namespace App\Infrastructure;

use DateTimeImmutable;
use PDO;
use App\Application\MeetingSpecification;
use App\Domain\Meeting;
use App\Domain\MeetingDuration;
use App\Domain\Program;
use App\Domain\ProgramSlot;
use App\Domain\ProgramSlotDuration;
use App\Domain\Title;
use Ramsey\Uuid\UuidInterface;

class DbMeetingRepository implements MeetingRepository
{
    private $dbConnection;

    /**
     * DbMeetingRepository constructor.
     */
    public function __construct(PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function getMeeting(UuidInterface $meetingId): Meeting
    {
//        $resultset = $this->dbConnection->query();
        $description = $resultset['description'];
        return new Meeting($meetingId,
            new Title('$title'),
            $description,
            new MeetingDuration(
                new DateTimeImmutable('2020-02-10 20:00'),
                new DateTimeImmutable('2020-02-20 20:20')
            ),
            new Program([
                new ProgramSlot(
                    new ProgramSlotDuration(
                        new DateTimeImmutable('2020-02-10 20:00'),
                        new DateTimeImmutable('2020-02-20 20:20')
                    ),
                    '$title',
                    '$room'
                )
            ]),
            10
        );
    }

    public function save(Meeting $meeting): void
    {
        // TODO: Implement save() method.
    }

    public function findBySpec(MeetingSpecification $spec): array
    {
        // TODO: Implement findBySpec() method.
    }
}