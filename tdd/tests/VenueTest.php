<?php

declare(strict_types=1);

namespace App\Tests;

use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;
use App\Domain\MeetingDuration;
use App\Domain\Program;
use App\Domain\ProgramSlot;
use App\Domain\ProgramSlotDuration;
use App\Domain\Title;
use App\Domain\Venue;
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
                Uuid::uuid4(),
                new ProgramSlotDuration(
                    new DateTimeImmutable('2020-01-01 19:00'),
                    new DateTimeImmutable('2020-01-01 20:00')
                ),
                'Divergence',
                'Main room'
            ),
            new ProgramSlot(
                Uuid::uuid4(),
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
                Uuid::uuid4(),
                new ProgramSlotDuration(
                    new DateTimeImmutable('2020-01-01 19:00'),
                    new DateTimeImmutable('2020-01-01 20:00')
                ),
                'Divergence',
                'Main room'
            ),
            new ProgramSlot(
                Uuid::uuid4(),
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
        $this->expectExceptionMessage('Venue already booked for this time period');

        $actual->bookForMeeting(
            $newMeetingId,
            $newTitle,
            $newDescription,
            $newDuration,
            $newProgram,
            $newMaxAttendees
        );
    }

    public function testThatMeetingCanBeRescheduled(): void
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
                Uuid::uuid4(),
                new ProgramSlotDuration(
                    new DateTimeImmutable('2020-01-01 19:00'),
                    new DateTimeImmutable('2020-01-01 20:00')
                ),
                'Divergence',
                'Main room'
            ),
            new ProgramSlot(
                Uuid::uuid4(),
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
            new DateTimeImmutable('2020-01-20 20:00'),
            new DateTimeImmutable('2020-01-20 23:00')
        );
        $newProgram = new Program([
            new ProgramSlot(
                Uuid::uuid4(),
                new ProgramSlotDuration(
                    new DateTimeImmutable('2020-01-01 19:00'),
                    new DateTimeImmutable('2020-01-01 20:00')
                ),
                'Divergence',
                'Main room'
            ),
            new ProgramSlot(
                Uuid::uuid4(),
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
        $this->expectExceptionMessage('Venue already booked for this time period');
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
