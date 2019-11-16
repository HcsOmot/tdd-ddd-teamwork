<?php
declare(strict_types=1);

namespace Procurios\Meeting\test;

use DateTimeImmutable;
use Procurios\Meeting\Description;
use Procurios\Meeting\Meeting;
use Procurios\Meeting\MeetingDuration;
use Procurios\Meeting\MeetingId;
use Procurios\Meeting\Program;
use Procurios\Meeting\ProgramSlot;
use PHPUnit\Framework\TestCase;
use Procurios\Meeting\SlotDuration;
use Procurios\Meeting\Title;
use Ramsey\Uuid\Uuid;

final class MeetingTest extends TestCase
{
    public function testThatValidMeetingsCanBeInstantiated()
    {
        $title = new Title('TDD, DDD & Teamwork');
        $description = new Description('This is a silly workshop, don\'t come');
        $meetingStart = new DateTimeImmutable('2017-12-15 19:00');
        $meetingEnd = new DateTimeImmutable('2017-12-15 21:00');
        $meetingDuration = new MeetingDuration($meetingStart, $meetingEnd);
        $meetingId = new MeetingId(Uuid::uuid4());

        $this->assertInstanceOf(Meeting::class, new Meeting(
            $meetingId,
            $title,
            $description,
            $meetingDuration,
            new Program([
                new ProgramSlot(
                    new SlotDuration(
                        new DateTimeImmutable('2017-12-15 19:00'),
                        new DateTimeImmutable('2017-12-15 20:00')
                    ),
                    'Divergence',
                    'Main room'
                ),
                new ProgramSlot(
                    new SlotDuration(
                        new DateTimeImmutable('2017-12-15 20:00'),
                        new DateTimeImmutable('2017-12-15 21:00')
                    ),
                    'Convergence',
                    'Main room'
                ),
            ])
        ));
    }

    public function testThatMeetingCanBeRescheduled()
    {
        $title = new Title('TDD, DDD & Teamwork');
        $description = new Description('This is a silly workshop, don\'t come');
        $meetingStart = new DateTimeImmutable('2017-12-15 19:00');
        $meetingEnd = new DateTimeImmutable('2017-12-15 21:00');
        $meetingDuration = new MeetingDuration(
            $meetingStart,
            $meetingEnd
        );
        $meetingId = new MeetingId(Uuid::uuid4());

        $meeting = new Meeting(
            $meetingId,
            $title,
            $description,
            $meetingDuration,
            new Program([
                new ProgramSlot(
                    new SlotDuration(
                        new DateTimeImmutable('2017-12-15 19:00'),
                        new DateTimeImmutable('2017-12-15 20:00')
                    ),
                    'Divergence',
                    'Main room'
                ),
            ])
        );

        $rescheduledMeetingDuration = new MeetingDuration(
            new DateTimeImmutable('2017-12-15 21:00'),
            new DateTimeImmutable('2017-12-15 23:00')
        );

        $expectation = new Meeting(
            $meetingId,
            $title,
            $description,
            $rescheduledMeetingDuration,
            new Program([
                new ProgramSlot(
                    new SlotDuration(
                        new DateTimeImmutable('2017-12-15 21:00'),
                        new DateTimeImmutable('2017-12-15 22:00')
                    ),
                    'Divergence',
                    'Main room'
                ),
            ])
        );

        $newMeetingStart = new DateTimeImmutable('2017-12-15 21:00');

        $rescheduledMeeting = $meeting->reschedule($newMeetingStart);

        $this->assertEquals($expectation, $rescheduledMeeting);
    }

    public function testThatMeetingWithTwoProgramSlotsCanBeRescheduled()
    {
        $title = new Title('TDD, DDD & Teamwork');
        $description = new Description('This is a silly workshop, don\'t come');
        $meetingStart = new DateTimeImmutable('2017-12-15 19:00');
        $meetingEnd = new DateTimeImmutable('2017-12-15 21:00');
        $meetingDuration = new MeetingDuration(
            $meetingStart,
            $meetingEnd
        );
        $meetingId = new MeetingId(Uuid::uuid4());

        $meeting = new Meeting(
            $meetingId,
            $title,
            $description,
            $meetingDuration,
            new Program([
                new ProgramSlot(
                    new SlotDuration(
                        new DateTimeImmutable('2017-12-15 19:00'),
                        new DateTimeImmutable('2017-12-15 20:00')
                    ),
                    'Divergence',
                    'Main room'
                ),
                new ProgramSlot(
                    new SlotDuration(
                        new DateTimeImmutable('2017-12-15 20:00'),
                        new DateTimeImmutable('2017-12-15 21:00')
                    ),
                    'Convergence',
                    'Main room'
                ),
            ])
        );

        $rescheduledMeetingDuration = new MeetingDuration(
            new DateTimeImmutable('2017-12-15 21:00'),
            new DateTimeImmutable('2017-12-15 23:00')
        );

        $expectation = new Meeting(
            $meetingId,
            $title,
            $description,
            $rescheduledMeetingDuration,
            new Program([
                new ProgramSlot(
                    new SlotDuration(
                        new DateTimeImmutable('2017-12-15 21:00'),
                        new DateTimeImmutable('2017-12-15 22:00')
                    ),
                    'Divergence',
                    'Main room'
                ),
                new ProgramSlot(
                    new SlotDuration(
                        new DateTimeImmutable('2017-12-15 22:00'),
                        new DateTimeImmutable('2017-12-15 23:00')
                    ),
                    'Divergence',
                    'Main room'
                ),
            ])
        );

        $newMeetingStart = new DateTimeImmutable('2017-12-15 21:00');

        $rescheduledMeeting = $meeting->reschedule($newMeetingStart);

        $this->assertEquals($expectation, $rescheduledMeeting);
    }
}
