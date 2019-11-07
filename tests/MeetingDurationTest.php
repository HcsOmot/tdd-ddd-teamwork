<?php

declare(strict_types=1);

namespace Procurios\Meeting\test;

use DateTimeImmutable;
use DomainException;
use Procurios\Meeting\MeetingDuration;
use Procurios\Meeting\MeetingEnd;
use Procurios\Meeting\MeetingStart;

class MeetingDurationTest extends \PHPUnit_Framework_TestCase
{
    public function testThatTheDurationHasStartAndEnd()
    {
        $startDate = new DateTimeImmutable();
        $meetingStart = new MeetingStart($startDate);
        $endDate = $startDate->modify('+1 hour');
        $meetingEnd = new MeetingEnd($endDate);

        $sut = new MeetingDuration($meetingStart, $meetingEnd);

        $this->assertInstanceOf(MeetingDuration::class, $sut);

        $this->assertNotNull($sut->from());
        $this->assertNotNull($sut->until());
    }

    public function testStartCannotBeAfterEnd()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Meeting cannot start after it ends.');

        $startDate = new DateTimeImmutable();
        $meetingStart = new MeetingStart($startDate);
        $endDate = $startDate->modify('-1 hour');
        $meetingEnd = new MeetingEnd($endDate);

        new MeetingDuration($meetingStart, $meetingEnd);
    }
}
