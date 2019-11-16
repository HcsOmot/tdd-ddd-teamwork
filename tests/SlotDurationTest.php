<?php

declare(strict_types=1);

namespace Procurios\Meeting\test;

use DateTimeImmutable;
use DomainException;
use Procurios\Meeting\SlotDuration;

class SlotDurationTest extends \PHPUnit_Framework_TestCase
{
    public function testThatTheDurationHasStartAndEnd()
    {
        $slotStart = new DateTimeImmutable();
        $slotEnd = $slotStart->modify('+1 hour');

        $sut = new SlotDuration($slotStart, $slotEnd);

        $this->assertInstanceOf(SlotDuration::class, $sut);

        $this->assertNotNull($sut->from());
        $this->assertNotNull($sut->until());
    }

    public function testStartCannotBeAfterEnd()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Slot cannot end before it starts.');

        $slotStart = new DateTimeImmutable();
        $slotEnd = $slotStart->modify('-1 hour');

        new SlotDuration($slotStart, $slotEnd);
    }

    public function testThatSlotDurationsCanBeCompared()
    {
        $slotAStart = new DateTimeImmutable();
        $slotAEnd = $slotAStart->modify('+1 hour');

        $slotBStart = new DateTimeImmutable();
        $slotBEnd = $slotBStart->modify('+1 hour');

        $slotADuration = new SlotDuration($slotAStart, $slotAEnd);
        $slotBDuration = new SlotDuration($slotBStart, $slotBEnd);

        $this->assertTrue($slotADuration->overlapsWith($slotBDuration));
    }

    public function testThatSlotDurationCanBeRescheduled()
    {
        $slotDuration = new SlotDuration(
            new DateTimeImmutable('2017-12-15 19:00'),
            new DateTimeImmutable('2017-12-15 20:00')
        );

        $expected = new SlotDuration(
            new DateTimeImmutable('2017-12-15 21:00'),
            new DateTimeImmutable('2017-12-15 22:00')
        );

        $rescheduled = $slotDuration->rescheduledTo(new DateTimeImmutable('2017-12-15 21:00'));

        $this->assertEquals($expected, $rescheduled);
    }
}
