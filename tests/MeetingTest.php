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
use Procurios\Meeting\Title;
use Ramsey\Uuid\Uuid;

final class MeetingTest extends TestCase
{
    public function testThatValidMeetingsCanBeInstantiated()
    {
        $title = new Title('TDD, DDD & Teamwork');
        $description = new Description('This is a silly workshop, don\'t come');
        $start = new DateTimeImmutable('2017-12-15 19:00');
        $end = new DateTimeImmutable('2017-12-15 21:00');
        $meetingDuration = new MeetingDuration($start, $end);
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
}
