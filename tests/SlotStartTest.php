<?php
declare(strict_types=1);

namespace Procurios\Meeting\test;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Procurios\Meeting\SlotStart;

final class SlotStartTest extends TestCase
{
    public function testThatStartCanBeCreatedFromDateTimeImmutable()
    {
        $startDate = new DateTimeImmutable('now');
        $slotStart = new SlotStart($startDate);
        $this->assertInstanceOf(SlotStart::class, $slotStart);
    }

    public function testThatSlotStartsCanBeCompared()
    {
        $startDate1 = new DateTimeImmutable('now');
        $slot1Start = new SlotStart($startDate1);

        $startDate2 = $startDate1->modify('+1 hours');
        $slot2Start = new SlotStart($startDate2);

        $this->assertTrue($slot1Start->isBefore($slot2Start));
    }
}
