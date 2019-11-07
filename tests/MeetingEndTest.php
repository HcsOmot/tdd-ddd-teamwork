<?php
declare(strict_types=1);

namespace Procurios\Meeting\test;

use DateTimeImmutable;
use Procurios\Meeting\MeetingEnd;
use PHPUnit\Framework\TestCase;

final class MeetingEndTest extends TestCase
{
    public function testThatEndCanBeCreatedFromDateTimeImmutable()
    {
        $endDate = new DateTimeImmutable('now');
        $meetingEnd = new MeetingEnd($endDate);
        $this->assertInstanceOf(MeetingEnd::class, $meetingEnd);
    }
}
