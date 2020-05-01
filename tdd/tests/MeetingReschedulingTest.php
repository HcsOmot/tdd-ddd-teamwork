<?php

declare(strict_types=1);

namespace App\Tests;


use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use App\Application\CreateMeetingCommand;
use App\Application\CreateMeetingCommandHandler;
use App\Application\CreateVenueCommand;
use App\Application\CreateVenueCommandHandler;
use App\Application\PutMeetingIntoVenueCommand;
use App\Application\PutMeetingIntoVenueCommandHandler;
use App\Application\RescheduleMeetingCommand;
use App\Application\RescheduleMeetingCommandHandler;
use App\Domain\MeetingDuration;
use App\Domain\MeetingOverlapException;
use App\Domain\Program;
use App\Domain\ProgramSlot;
use App\Domain\ProgramSlotDuration;
use App\Domain\Title;
use App\Infrastructure\InMemoryMeetingRepository;
use App\Infrastructure\InMemoryVenueRepository;
use App\Infrastructure\MeetingAtAVenueRepository;
use Ramsey\Uuid\Uuid;

/**
 * @coversNothing
 *
 * @small
 */
class MeetingReschedulingTest extends TestCase
{
    /** @var PutMeetingIntoVenueCommandHandler */
    private $putMeetingIntoVenueCommandHandler;
    /** @var CreateMeetingCommandHandler */
    private $createMeetingCommandHandler;
    private $createVenueCommandHandler;
    private $rescheduleMeetingCommandHandler;

    protected function setUp(): void
    {
        $venueRepository = new InMemoryVenueRepository();
        $meetingRepository = new InMemoryMeetingRepository();

        $meetingInAVenueRepository = new MeetingAtAVenueRepository();

        $this->putMeetingIntoVenueCommandHandler = new PutMeetingIntoVenueCommandHandler(
            $meetingRepository,
            $venueRepository,
            $meetingInAVenueRepository
        );

        $this->createMeetingCommandHandler = new CreateMeetingCommandHandler($meetingRepository);
        $this->rescheduleMeetingCommandHandler = new RescheduleMeetingCommandHandler(
            $meetingRepository,
            $venueRepository,
            $meetingInAVenueRepository
        );
        $this->createVenueCommandHandler = new CreateVenueCommandHandler($venueRepository);
    }

    public function testThatPuttingTwoMeetingsThatOverlapIntoVenueWillRaiseException(): void
    {
        $meetingId1 = Uuid::uuid4();
        $meetingId2 = Uuid::uuid4();
        $venueId = (string) Uuid::uuid4();

        $createMeetingCommand1 = new CreateMeetingCommand(
            $meetingId1,
            new Title('$title'),
            '$description',
            new MeetingDuration(
                new DateTimeImmutable('2020-02-10 20:20'),
                new DateTimeImmutable('2020-02-20 20:20')
            ),
            new Program(
                [
                    new ProgramSlot(
                        Uuid::uuid4(),
                        new ProgramSlotDuration(
                            new DateTimeImmutable('2020-02-10 20:20'),
                            new DateTimeImmutable('2020-02-20 20:20')
                        ),
                        '$title',
                        '$room'
                    ),
                ]
            ),
            10
        );

        $createMeetingCommand2 = new CreateMeetingCommand(
            $meetingId2,
            new Title('$title'),
            '$description',
            new MeetingDuration(
                new DateTimeImmutable('2020-02-10 20:20'),
                new DateTimeImmutable('2020-02-20 20:20')
            ),
            new Program(
                [
                    new ProgramSlot(
                        Uuid::uuid4(),
                        new ProgramSlotDuration(
                            new DateTimeImmutable('2020-02-10 20:20'),
                            new DateTimeImmutable('2020-02-20 20:20')
                        ),
                        '$title',
                        '$room'
                    ),
                ]
            ),
            10
        );

        ($this->createMeetingCommandHandler)($createMeetingCommand1);
        ($this->createMeetingCommandHandler)($createMeetingCommand2);

        $createVenueCommand = new CreateVenueCommand($venueId, 'awesome venue');

        $this->createVenueCommandHandler->handle($createVenueCommand);

        $command1 = new PutMeetingIntoVenueCommand((string) $meetingId1, $venueId);
        $command2 = new PutMeetingIntoVenueCommand((string) $meetingId2, $venueId);

        $this->putMeetingIntoVenueCommandHandler->handle($command1);
        $this->expectException(MeetingOverlapException::class);
        $this->putMeetingIntoVenueCommandHandler->handle($command2);
    }

    public function testThatReschedulingAMeetingInABookedVenueWillRaiseException(): void
    {
        $meetingId1 = Uuid::uuid4();
        $meetingId2 = Uuid::uuid4();
        $venueId = (string) Uuid::uuid4();

        $createMeetingCommand1 = new CreateMeetingCommand(
            $meetingId1,
            new Title('$title'),
            '$description',
            new MeetingDuration(
                new DateTimeImmutable('2020-02-10 20:20'),
                new DateTimeImmutable('2020-02-20 20:20')
            ),
            new Program(
                [
                    new ProgramSlot(
                        Uuid::uuid4(),
                        new ProgramSlotDuration(
                            new DateTimeImmutable('2020-02-10 20:20'),
                            new DateTimeImmutable('2020-02-20 20:20')
                        ),
                        '$title',
                        '$room'
                    ),
                ]
            ),
            10
        );

        $createMeetingCommand2 = new CreateMeetingCommand(
            $meetingId2,
            new Title('$title'),
            '$description',
            new MeetingDuration(
                new DateTimeImmutable('2020-03-10 20:20'),
                new DateTimeImmutable('2020-03-20 20:20')
            ),
            new Program(
                [
                    new ProgramSlot(
                        Uuid::uuid4(),
                        new ProgramSlotDuration(
                            new DateTimeImmutable('2020-02-10 20:20'),
                            new DateTimeImmutable('2020-02-20 20:20')
                        ),
                        '$title',
                        '$room'
                    ),
                ]
            ),
            10
        );

        ($this->createMeetingCommandHandler)($createMeetingCommand1);
        ($this->createMeetingCommandHandler)($createMeetingCommand2);

        $createVenueCommand = new CreateVenueCommand($venueId, 'awesome venue');

        $this->createVenueCommandHandler->handle($createVenueCommand);

        $command1 = new PutMeetingIntoVenueCommand((string) $meetingId1, $venueId);
        $command2 = new PutMeetingIntoVenueCommand((string) $meetingId2, $venueId);
        $this->putMeetingIntoVenueCommandHandler->handle($command1);
        $this->putMeetingIntoVenueCommandHandler->handle($command2);
        
        $rescheduleMeeting = new RescheduleMeetingCommand(
            $meetingId1->toString(),
            '2020-03-15:12:00', 
            $venueId
        );
        
        $this->expectException(MeetingOverlapException::class);
        ($this->rescheduleMeetingCommandHandler)($rescheduleMeeting);
    }

    public function testItReschedulesMeetingWhenVenueAvailable(): void
    {
//        SPIKE:
//        setup 2 meetings with different durations
//        setup venue
//        save meetings in same venue
//        reschedule meeting 1 to duration x
//        reschedule meeting 2 to duration (overlap(x))
//        confirm exception

        $meetingId1 = Uuid::uuid4();
        $meetingId2 = Uuid::uuid4();
        
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
                    Uuid::uuid4(),
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

        $meeting2 = new CreateMeetingCommand(
            $meetingId2,
            new Title('$title'),
            '$description',
            new MeetingDuration(
                new DateTimeImmutable('2020-03-10 20:00'),
                new DateTimeImmutable('2020-03-20 20:20')
            ),
            new Program([
                new ProgramSlot(
                    Uuid::uuid4(),
                    new ProgramSlotDuration(
                        new DateTimeImmutable('2020-03-10 20:00'),
                        new DateTimeImmutable('2020-03-20 20:20')
                    ), 
                    '$title',
                    '$room'
                )
            ]),
            10
        );

        ($this->createMeetingCommandHandler)($meeting1);
        ($this->createMeetingCommandHandler)($meeting2);
        
        $venueId = Uuid::uuid4();
        $venueCommand = new CreateVenueCommand($venueId->toString(), 'awesome venue');
        $this->createVenueCommandHandler->handle($venueCommand);
        
        $meeting1AtVenue = new PutMeetingIntoVenueCommand(
            $meetingId1->toString(),
            $venueId->toString()
        );
        $meeting2AtVenue = new PutMeetingIntoVenueCommand(
            $meetingId2->toString(),
            $venueId->toString())
        ;
        
        $this->putMeetingIntoVenueCommandHandler->handle($meeting1AtVenue);
        $this->putMeetingIntoVenueCommandHandler->handle($meeting2AtVenue);
        
        $rescheduleMeeting1Command = new RescheduleMeetingCommand(
            $meetingId1->toString(),
            '2020-03-21:12:00', 
            $venueId->toString()
        );
        
        ($this->rescheduleMeetingCommandHandler)($rescheduleMeeting1Command);
        
        $rescheduleMeeting2Command = new RescheduleMeetingCommand(
            $meetingId2->toString(),
            '2020-03-22:12:00',
            $venueId->toString()
        );
        
        $this->expectException(MeetingOverlapException::class);
        ($this->rescheduleMeetingCommandHandler)($rescheduleMeeting2Command);
    }
}
