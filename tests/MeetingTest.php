<?php
declare(strict_types=1);

namespace Procurios\Meeting\test;

use DateTimeImmutable;
use Procurios\Meeting\Description;
use Procurios\Meeting\Meeting;
use Procurios\Meeting\MeetingDuration;
use Procurios\Meeting\MeetingEnd;
use Procurios\Meeting\MeetingId;
use Procurios\Meeting\MeetingStart;
use Procurios\Meeting\Program;
use Procurios\Meeting\ProgramSlot;
use PHPUnit\Framework\TestCase;
use Procurios\Meeting\Title;
use Ramsey\Uuid\Uuid;

final class MeetingTest extends TestCase
{
    public function testThatValidMeetingsCanBeInstantiated()
    {
        $title = new Title('TDD, DDD & Teamwork');
        $description = new Description('This is a silly workshop, don\'t come');
        $meetingStart = new MeetingStart(new DateTimeImmutable('2017-12-15 19:00'));
        $meetingEnd = new MeetingEnd(new DateTimeImmutable('2017-12-15 21:00'));
        $meetingDuration = new MeetingDuration($meetingStart, $meetingEnd);
        $meetingId = new MeetingId(Uuid::uuid4());

        $this->assertInstanceOf(Meeting::class, new Meeting(
            $meetingId,
            $title,
            $description,
            $meetingDuration,
            new Program([
                new ProgramSlot(
                    new DateTimeImmutable('2017-12-15 19:00'),
                    new DateTimeImmutable('2017-12-15 20:00'),
                    'Divergence',
                    'Main room'
                ),
                new ProgramSlot(
                    new DateTimeImmutable('2017-12-15 20:00'),
                    new DateTimeImmutable('2017-12-15 21:00'),
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
        $startDate = new DateTimeImmutable('2017-12-15 19:00');
        $meetingStart = new MeetingStart($startDate);
        $endDate = new DateTimeImmutable('2017-12-15 21:00');
        $meetingEnd = new MeetingEnd($endDate);
        $meetingDuration = new MeetingDuration($meetingStart, $meetingEnd);
        $meetingId = new MeetingId(Uuid::uuid4());

        $meeting = new Meeting(
            $meetingId,
            $title,
            $description,
            $meetingDuration,
            new Program([
                new ProgramSlot(
                    new DateTimeImmutable('2017-12-15 19:00'),
                    new DateTimeImmutable('2017-12-15 20:00'),
                    'Divergence',
                    'Main room'
                ),
                new ProgramSlot(
                    new DateTimeImmutable('2017-12-15 20:00'),
                    new DateTimeImmutable('2017-12-15 21:00'),
                    'Convergence',
                    'Main room'
                ),
            ])
        );

        $newStartDate = $startDate->modify('+2 hours');
        $newMeetingStart = new MeetingStart($newStartDate);
        $expectedEnding = new MeetingEnd($endDate->modify('+2 hours'));

        $meeting->reschedule($newMeetingStart);

        $meetingDuration = $meeting->getDuration();

        $this->assertEquals($expectedEnding, $meetingDuration->until());
    }
}
