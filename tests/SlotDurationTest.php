<?php

declare(strict_types=1);

namespace Procurios\Meeting\test;

use DateTimeImmutable;
use DomainException;
use Procurios\Meeting\MeetingDuration;
use Procurios\Meeting\MeetingEnd;
use Procurios\Meeting\MeetingStart;
use Procurios\Meeting\SlotDuration;
use Procurios\Meeting\SlotEnd;
use Procurios\Meeting\SlotStart;

class SlotDurationTest extends \PHPUnit_Framework_TestCase
{
    public function testThatTheDurationHasStartAndEnd()
    {
        $startDate = new DateTimeImmutable();
        $slotStart = new SlotStart($startDate);
        $endDate = $startDate->modify('+1 hour');
        $slotEnd = new SlotEnd($endDate);

        $sut = new SlotDuration($slotStart, $slotEnd);

        $this->assertInstanceOf(SlotDuration::class, $sut);

        $this->assertNotNull($sut->from());
        $this->assertNotNull($sut->until());
    }

    public function testStartCannotBeAfterEnd()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Slot cannot end before it starts.');

        $startDate = new DateTimeImmutable();
        $slotStart = new SlotStart($startDate);
        $endDate = $startDate->modify('-1 hour');
        $slotEnd = new SlotEnd($endDate);

        new SlotDuration($slotStart, $slotEnd);
    }

    public function testThatSlotDurationsCanBeCompared()
    {
        $startDate1 = new DateTimeImmutable();
        $slotAStart = new SlotStart($startDate1);
        $endDate1 = $startDate1->modify('+1 hour');
        $slotAEnd = new SlotEnd($endDate1);

        $startDate2 = new DateTimeImmutable();
        $slotBStart = new SlotStart($startDate2);
        $endDate2 = $startDate2->modify('+1 hour');
        $slotBEnd = new SlotEnd($endDate2);

        $slotADuration = new SlotDuration($slotAStart, $slotAEnd);
        $slotBDuration = new SlotDuration($slotBStart, $slotBEnd);

        $this->assertTrue($slotADuration->overlapsWith($slotBDuration));
    }
}
