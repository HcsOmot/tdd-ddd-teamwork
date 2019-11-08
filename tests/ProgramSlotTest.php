<?php

declare(strict_types=1);

namespace Procurios\Meeting\test;


use DateTimeImmutable;
use Procurios\Meeting\ProgramSlot;
use Procurios\Meeting\SlotDuration;
use Procurios\Meeting\SlotEnd;
use Procurios\Meeting\SlotStart;

class ProgramSlotTest extends \PHPUnit_Framework_TestCase
{
    public function testThatProgramSlotsCanBeComparedForOverlap()
    {
        $startAt5 = new SlotStart(new DateTimeImmutable('2017-12-15 17:00'));
        $endAt6 = new SlotEnd(new DateTimeImmutable('2017-12-15 18:00'));
        $duration5Till6 = new SlotDuration($startAt5, $endAt6);

        $startAt6 = new SlotStart(new DateTimeImmutable('2017-12-15 18:00'));
        $endAt7 = new SlotEnd(new DateTimeImmutable('2017-12-15 19:00'));
        $duration6Till7 = new SlotDuration($startAt6, $endAt7);

        $startAt7 = new SlotStart(new DateTimeImmutable('2017-12-15 19:00'));
        $endAt8 = new SlotEnd(new DateTimeImmutable('2017-12-15 20:00'));
        $duration7Till8 = new SlotDuration($startAt7, $endAt8);

        $roomA = 'room A';
        $roomB = 'room B';

        $roomA5Till6TDD = new ProgramSlot(
            $duration5Till6,
            'TDD',
            $roomA
        );

        $roomA5Till6Patterns = new ProgramSlot(
            $duration5Till6,
            'Patterns',
            $roomA
        );

        $roomB7Till8Refactoring = new ProgramSlot(
            $duration7Till8,
            'Refactoring',
            $roomB
        );

        $roomB5Till6Algorithms = new ProgramSlot(
            $duration6Till7,
            'Algorithms',
            $roomB
        );

        $this->assertTrue($roomA5Till6TDD->overlapsWith($roomA5Till6Patterns));
        $this->assertFalse($roomA5Till6TDD->overlapsWith($roomB7Till8Refactoring));
        $this->assertFalse($roomA5Till6TDD->overlapsWith($roomB5Till6Algorithms));
    }
}
