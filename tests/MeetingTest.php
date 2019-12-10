<?php
declare(strict_types=1);

namespace Procurios\Meeting\Tests;

use DateInterval;
use DateTimeImmutable;
use DomainException;
use InvalidArgumentException;
use Procurios\Meeting\Meeting;
use Procurios\Meeting\Meeting\ProgramSlotDuration;
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
        $this->assertInstanceOf(
            Meeting::class,
            new Meeting(
                Uuid::uuid4(),
                new Title('TDD, DDD & Teamwork'),
                'This is a silly workshop, don\'t come',
                new MeetingDuration(
                    new DateTimeImmutable('2020-01-01 19:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ),
                new Program([
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
                ])
            )
        );
    }

    public function testThatProgramOnlyAcceptsProgramSlots()
    {
        $this->expectException(InvalidArgumentException::class);
        new Program([new DateTimeImmutable('2020-01-01 19:00')]);
    }

    public function testThatMeetingCannotEndBeforeItStarted()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Meeting cannot end before it has started');

        new Meeting(
            Uuid::uuid4(),
            new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 21:00'),
                new DateTimeImmutable('2020-01-01 19:00')
            ),
            new Program([
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
            ])
        );
    }

    public function testThatMeetingMustHaveAtLeastOneProgrammeSlot()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Meeting must have at least one Programme Slot');

        new Meeting(
            Uuid::uuid4(),
            new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ),
            new Program([])
        );
    }

    public function testThatMeetingCanBeRescheduled()
    {
        $meetingId = Uuid::uuid4();

        $actual = new Meeting(
            $meetingId,
            new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ),
            new Program([
                new ProgramSlot(
                    new ProgramSlotDuration(
                        new DateTimeImmutable('2020-01-01 19:00'),
                        new DateTimeImmutable('2020-01-01 20:00')
                    ),
                    'Divergence',
                    'Main room'
                )
            ])
        );

        $expected = new Meeting(
            $meetingId,
            new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-02 12:00'),
                new DateTimeImmutable('2020-01-02 14:00')
            ),
            new Program([
                new ProgramSlot(
                    new ProgramSlotDuration(
                        new DateTimeImmutable('2020-01-02 12:00'),
                        new DateTimeImmutable('2020-01-02 13:00')
                    ),
                    'Divergence',
                    'Main room'
                )
            ])
        );

        $rescheduledStart = new DateTimeImmutable('2020-01-02 12:00');
        $actual->rescheduleFor($rescheduledStart);
        $this->assertEquals($expected, $actual);
    }

    public function testThatMultipleProgramSlotsInMeetingWillBeRescheduled()
    {
        $meetingId = Uuid::uuid4();

        $actual = new Meeting(
            $meetingId,
            new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ),
            new Program([
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
                    'Divergence',
                    'Main room'
                )
            ])
        );

        $expected = new Meeting(
            $meetingId,
            new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-02 12:00'),
                new DateTimeImmutable('2020-01-02 14:00')
            ),
            new Program([
                new ProgramSlot(
                    new ProgramSlotDuration(
                        new DateTimeImmutable('2020-01-02 12:00'),
                        new DateTimeImmutable('2020-01-02 13:00')
                    ),
                    'Divergence',
                    'Main room'
                ),
                new ProgramSlot(
                    new ProgramSlotDuration(
                        new DateTimeImmutable('2020-01-02 13:00'),
                        new DateTimeImmutable('2020-01-02 14:00')
                    ),
                    'Divergence',
                    'Main room'
                )
            ])
        );

        $rescheduledStart = new DateTimeImmutable('2020-01-02 12:00');
        $actual->rescheduleFor($rescheduledStart);
        $this->assertEquals($expected, $actual);
    }

    public function testThatTwoSameProgramSlotsCannotOverlap()
    {
        $this->expectException(DomainException::class);

        new Meeting(
            Uuid::uuid4(),
            new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ),
            new Program([
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
                        new DateTimeImmutable('2020-01-01 19:00'),
                        new DateTimeImmutable('2020-01-01 20:00')
                    ),
                    'Convergence',
                    'Main room'
                )
            ])
        );
    }

    public function testThatProgramSlotsCannotOverlap()
    {
        $this->expectException(DomainException::class);

        new Meeting(
            Uuid::uuid4(),
            new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ),
            new Program([
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
                        new DateTimeImmutable('2020-01-01 19:30:00'),
                        new DateTimeImmutable('2020-01-01 21:00')
                    ),
                    'Convergence',
                    'Main room'
                )
            ])
        );
    }

}
