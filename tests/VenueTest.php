<?php

declare(strict_types=1);

namespace Procurios\Meeting\Tests;

use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;
use Procurios\Meeting\MeetingDuration;
use Procurios\Meeting\Program;
use Procurios\Meeting\ProgramSlot;
use Procurios\Meeting\ProgramSlotDuration;
use Procurios\Meeting\Title;
use Procurios\Meeting\Venue;
use Ramsey\Uuid\Uuid;

/**
 * @coversNothing
 *
 * @small
 */
class VenueTest extends TestCase
{
    public function testThatVenueCanBeCreated(): void
    {
        static::assertInstanceOf(
            Venue::class,
            new Venue(
                Uuid::uuid4(),
                'City Plaza Zagreb'
            )
        );
    }

    public function testThatVenueCanBeBookedForMeeting(): void
    {
        $actual = new Venue(
            Uuid::uuid4(),
            'City Plaza Zagreb'
        );

        $meetingId = Uuid::uuid4();
        $title = new Title('TDD, DDD & Teamwork');
        $description = 'This is a silly workshop, don\'t come';
        $duration = new MeetingDuration(
            new DateTimeImmutable('2020-01-01 19:00'),
            new DateTimeImmutable('2020-01-01 21:00')
        );
        $program = new Program([
            new ProgramSlot(
                new ProgramSlotDuration(
                    new DateTimeImmutable('2020-01-01 19:00'),
                    new DateTimeImmutable('2020-01-01 20:00')
                ),
                'Divergence',
                'Main room'
            ),
            new ProgramSlot(
                new ProgramSlotDuration(
                    new DateTimeImmutable('2020-01-01 20:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ),
                'Convergence',
                'Main room'
            ),
        ]);
        $maxAttendees = 10;

        $actual->bookForMeeting(
            $meetingId,
            $title,
            $description,
            $duration,
            $program,
            $maxAttendees
        );

        $newMeetingId = Uuid::uuid4();
        $newTitle = new Title('TDD, DDD & Teamwork');
        $newDescription = 'This is a silly workshop, don\'t come';
        $newDuration = new MeetingDuration(
            new DateTimeImmutable('2020-01-01 19:00'),
            new DateTimeImmutable('2020-01-01 21:00')
        );
        $newProgram = new Program([
            new ProgramSlot(
                new ProgramSlotDuration(
                    new DateTimeImmutable('2020-01-01 19:00'),
                    new DateTimeImmutable('2020-01-01 20:00')
                ),
                'Divergence',
                'Main room'
            ),
            new ProgramSlot(
                new ProgramSlotDuration(
                    new DateTimeImmutable('2020-01-01 20:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ),
                'Convergence',
                'Main room'
            ),
        ]);
        $newMaxAttendees = 10;

        $this->expectException(DomainException::class);
        $actual->bookForMeeting(
            $newMeetingId,
            $newTitle,
            $newDescription,
            $newDuration,
            $newProgram,
            $newMaxAttendees
        );
    }

    public function testThatMeetingCanBeRescheduledVENUEINTERNAL(): void
    {
        $actual = new Venue(
            Uuid::uuid4(),
            'City Plaza Zagreb'
        );

        $meetingId = Uuid::uuid4();
        $title = new Title('TDD, DDD & Teamwork');
        $description = 'This is a silly workshop, don\'t come';
        $duration = new MeetingDuration(
            new DateTimeImmutable('2020-01-01 19:00'),
            new DateTimeImmutable('2020-01-01 21:00')
        );
        $program = new Program([
            new ProgramSlot(
                new ProgramSlotDuration(
                    new DateTimeImmutable('2020-01-01 19:00'),
                    new DateTimeImmutable('2020-01-01 20:00')
                ),
                'Divergence',
                'Main room'
            ),
            new ProgramSlot(
                new ProgramSlotDuration(
                    new DateTimeImmutable('2020-01-01 20:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ),
                'Convergence',
                'Main room'
            ),
        ]);
        $maxAttendees = 10;

        $actual->bookForMeeting(
            $meetingId,
            $title,
            $description,
            $duration,
            $program,
            $maxAttendees
        );

        $actual->moveMeetingBooking(
            $meetingId,
            new DateTimeImmutable('2020-01-20 19:00')
        );

        $newMeetingId = Uuid::uuid4();
        $newTitle = new Title('TDD, DDD & Teamwork');
        $newDescription = 'This is a silly workshop, don\'t come';
        $newDuration = new MeetingDuration(
            new DateTimeImmutable('2020-01-20 19:00'),
            new DateTimeImmutable('2020-01-20 21:00')
        );
        $newProgram = new Program([
            new ProgramSlot(
                new ProgramSlotDuration(
                    new DateTimeImmutable('2020-01-01 19:00'),
                    new DateTimeImmutable('2020-01-01 20:00')
                ),
                'Divergence',
                'Main room'
            ),
            new ProgramSlot(
                new ProgramSlotDuration(
                    new DateTimeImmutable('2020-01-01 20:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ),
                'Convergence',
                'Main room'
            ),
        ]);
        $newMaxAttendees = 10;

        $this->expectException(DomainException::class);
        $actual->bookForMeeting(
            $newMeetingId,
            $newTitle,
            $newDescription,
            $newDuration,
            $newProgram,
            $newMaxAttendees
        );
    }
}
