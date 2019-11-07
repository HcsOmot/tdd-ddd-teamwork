<?php
declare(strict_types=1);

namespace Procurios\Meeting\test;

use DateTime;
use DateTimeImmutable;
use DomainException;
use Procurios\Meeting\Description;
use Procurios\Meeting\Meeting;
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
        $this->assertInstanceOf(Meeting::class, new Meeting(
            Uuid::uuid4(),
            $title,
            $description,
            new DateTimeImmutable('2017-12-15 19:00'),
            new DateTimeImmutable('2017-12-15 21:00'),
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

    public function testThatMeetingCannotHaveStartDateLaterThanEndDate()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Meeting cannot start after it ends.');
        $title = new Title('Starting later than ending...');
        $description = new Description('... should not be allowed.');
        new Meeting(
            Uuid::uuid4(),
            $title,
            $description,
            DateTimeImmutable::createFromMutable(
                (new DateTime('now'))
                    ->modify('+1 day')
            ),
            DateTimeImmutable::createFromMutable(new DateTime('now')),
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
    }
}
