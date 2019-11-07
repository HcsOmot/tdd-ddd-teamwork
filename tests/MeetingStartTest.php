<?php
declare(strict_types=1);

namespace Procurios\Meeting\test;

use DateTimeImmutable;
use Procurios\Meeting\MeetingStart;
use PHPUnit\Framework\TestCase;

final class MeetingStartTest extends TestCase
{
    public function testThatStartCanBeCreatedFromDateTimeImmutable()
    {
        $startDate = new DateTimeImmutable('now');
        $meetingStart = new MeetingStart($startDate);
        $this->assertInstanceOf(MeetingStart::class, $meetingStart);
    }
}
