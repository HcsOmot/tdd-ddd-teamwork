<?php

declare(strict_types=1);

namespace App\Tests;

use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use PDO;
use PHPUnit\Framework\TestCase;
use App\Application\CreateMeetingCommand;
use App\Application\CreateMeetingCommandHandler;
use App\Domain\Meeting;
use App\Domain\MeetingDuration;
use App\Domain\Program;
use App\Domain\ProgramSlot;
use App\Domain\ProgramSlotDuration;
use App\Domain\Title;
use App\Infrastructure\DbMeetingRepository;
use Ramsey\Uuid\Uuid;

class DbMeetingRepositoryTest extends TestCase
{

    private $createMeetingCommandHandler;
    /** @var DbMeetingRepository */
    private $meetingRepository;

    public function setUp(): void
    {
        parent::setUp();
        $entityManager = static::$container->get(EntityManagerInterface::class);

        $repo = $entityManager->getRepository(Meeting::class);

        $this->meetingRepository = $repo;
        $this->createMeetingCommandHandler = new CreateMeetingCommandHandler($this->meetingRepository);
    }

    public function testItCanRetrieveASavedMeeting()
    {
        $this->markTestSkipped('not doing this atm, moving to sf&doctrine');
//        1. create a new meeting 
//        2. save it trhough CH
//        3. retrieve it through the repo

        $meetingId1 = Uuid::uuid4();

        $meeting1 = new CreateMeetingCommand(
            $meetingId1,
            new Title('$title'),
            '$description',
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

        ($this->createMeetingCommandHandler)($meeting1);
        
        $actual = new Meeting($meetingId1,
            new Title('$title'),
            '$description',
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
        
        $expected = $this->meetingRepository->getMeeting($meetingId1);
        
        $this->assertEquals($expected, $actual);
    }
}
