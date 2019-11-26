<?php
declare(strict_types=1);

namespace Procurios\Meeting\test;

use DateTimeImmutable;
use DomainException;
use InvalidArgumentException;
use Procurios\Meeting\Meeting;
use Procurios\Meeting\MeetingDuration;
use Procurios\Meeting\Program;
use Procurios\Meeting\ProgramSlot;
use PHPUnit\Framework\TestCase;
use Procurios\Meeting\Title;
use Ramsey\Uuid\Uuid;

final class MeetingTest extends TestCase
{
    public function testThatValidMeetingsCanBeInstantiated()
    {
        $this->assertInstanceOf(Meeting::class, new Meeting(
            Uuid::uuid4(),
            new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2017-12-15 19:00'),
                new DateTimeImmutable('2017-12-15 21:00')
            ),
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

    public function testThatMeetingCannotHaveAnEmptyTitle()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title must have at least five characters.');

        new Meeting(
            Uuid::uuid4(),
            new Title(''),
            'Meeting description',
            new MeetingDuration(
                new DateTimeImmutable('2019-12-15 21:00'),
                new DateTimeImmutable('2019-01-15 19:00')
            ),
            new Program(
                [
                    new ProgramSlot(
                        new DateTimeImmutable('2017-12-15 19:00'),
                        new DateTimeImmutable('2017-12-15 20:00'),
                        'Divergence',
                        'Main room'
                    ),
                ]
            )
        );
    }

    public function testThatMeetingCannotEndBeforeItStarts()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Meeting cannot end before it started.');
        
        new Meeting(
            Uuid::uuid4(),
            new Title('meeting title'),
            'Meeting description',
            new MeetingDuration(
                new DateTimeImmutable('2019-12-15 21:00'),
                new DateTimeImmutable('2019-01-15 19:00')
            ),
            new Program(
                [
                    new ProgramSlot(
                        new DateTimeImmutable('2017-12-15 19:00'),
                        new DateTimeImmutable('2017-12-15 20:00'),
                        'Divergence',
                        'Main room'
                    ),
                ]
            )
        );
    }

    public function testThatMeetingMustHaveAtLeastOneProgramSlot()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Program must have at least one program slot.');
        
        new Meeting(
            Uuid::uuid4(),
            new Title('meeting title'),
            'Meeting description',
            new MeetingDuration(
                new DateTimeImmutable('2019-12-15 19:00'),
                new DateTimeImmutable('2019-12-15 21:00')
            ),
            new Program(
                []
            )
        );
    }

    public function testThatMeetingCanBeRescheduled()
    {
        $actual = new Meeting(
            Uuid::uuid4(),
            new Title('meeting title'),
            'Meeting description',
            new MeetingDuration(
                new DateTimeImmutable('2019-01-15 19:00'),
                new DateTimeImmutable('2019-02-15 21:00')
            ),
            new Program(
                [
                    new ProgramSlot(
                        new DateTimeImmutable('2017-01-15 19:00'),
                        new DateTimeImmutable('2017-02-15 20:00'),
                        'Divergence',
                        'Main room'
                    ),
                ]
            )
        );
        
        $expected = new Meeting(
            Uuid::uuid4(),
            new Title('meeting title'),
            'Meeting description',
            new MeetingDuration(
                new DateTimeImmutable('2019-01-15 19:00'),
                new DateTimeImmutable('2019-12-15 21:00')
            ),
            new Program(
                [
                    new ProgramSlot(
                        new DateTimeImmutable('2017-12-15 19:00'),
                        new DateTimeImmutable('2017-12-15 20:00'),
                        'Divergence',
                        'Main room'
                    ),
                ]
            )
        );
    }
}
