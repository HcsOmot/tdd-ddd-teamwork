<?php

declare(strict_types=1);

namespace Procurios\Meeting\test;

use DateTimeImmutable;
use InvalidArgumentException;
use Procurios\Meeting\Program;
use Procurios\Meeting\ProgramSlot;
use Procurios\Meeting\SlotDuration;

class ProgramTest extends \PHPUnit_Framework_TestCase
{
    public function testThatProgramCannotBeCreatedWithoutAnyProgramSlots()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one ProgramSlot is required.');

        new Program([]);
    }

    public function testThatProgramCanBeRescheduled()
    {
        $this->markTestSkipped();
        $programSlotA = new ProgramSlot(
            new SlotDuration(
                new DateTimeImmutable('2017-12-15 19:00'),
                new DateTimeImmutable('2017-12-15 20:00')
            ),
            'Slot A',
            'room A'
        );

        $programSlotB = new ProgramSlot(
            new SlotDuration(
                new DateTimeImmutable('2017-12-15 20:00'),
                new DateTimeImmutable('2017-12-15 21:00')
            ),
            'Slot B',
            'room A'
        );

        $program = new Program([$programSlotB]);


        $rescheduledSlotA = new ProgramSlot(
            new SlotDuration(
                new DateTimeImmutable('2017-12-15 20:00'),
                new DateTimeImmutable('2017-12-15 21:00')
            ),
            'Slot A',
            'room A'
        );

        $rescheduledSlotB = new ProgramSlot(
            new SlotDuration(
                new DateTimeImmutable('2017-12-15 21:00'),
                new DateTimeImmutable('2017-12-15 22:00')
            ),
            'Slot B',
            'room A'
        );

        $expected = new Program([$rescheduledSlotB]);

        $rescheduled = $program->rescheduledTo(new DateTimeImmutable('2017-12-15 20:00'));

        $this->assertEquals($expected, $rescheduled);
    }

    public function testThatSecondSlotIsNotMovedToTheStartOfTheProgram() {
        $actual = new Program([
            new ProgramSlot(
                new SlotDuration(
                    new DateTimeImmutable('2019-01-01 18:30'),
                    new DateTimeImmutable('2019-01-01 19:30')
                ),
                '$title',
                '$room'
            ),
            new ProgramSlot(
                new SlotDuration(
                    new DateTimeImmutable('2019-01-01 19:30'),
                    new DateTimeImmutable('2019-01-01 20:30')
                ),
                '$title',
                '$room'
            )
        ]);
        $expected = new Program([
            new ProgramSlot(
                new SlotDuration(
                    new DateTimeImmutable('2019-02-01 19:30'),
                    new DateTimeImmutable('2019-02-01 20:30')
                ),
                '$title',
                '$room'
            ),
            new ProgramSlot(
                new SlotDuration(
                    new DateTimeImmutable('2019-02-01 20:30'),
                    new DateTimeImmutable('2019-02-01 21:30')
                ),
                '$title',
                '$room'
            )
            ]
        );

        $actual = $actual->rescheduledBy((new DateTimeImmutable('2019-01-01 18:30'))->diff(new DateTimeImmutable('2019-02-01 19:30')));

        $this->assertEquals($expected, $actual);
    }
}
