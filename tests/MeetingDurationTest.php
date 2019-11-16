<?php

declare(strict_types=1);

namespace Procurios\Meeting\test;

use DateTimeImmutable;
use DomainException;
use Procurios\Meeting\MeetingDuration;

class MeetingDurationTest extends \PHPUnit_Framework_TestCase
{
    public function testThatTheDurationHasStartAndEnd()
    {
        $meetingStart = new DateTimeImmutable();
        $meetingEnd = $meetingStart->modify('+1 hour');

        $sut = new MeetingDuration($meetingStart, $meetingEnd);

        $this->assertInstanceOf(MeetingDuration::class, $sut);

        $this->assertNotNull($sut->from());
        $this->assertNotNull($sut->until());
    }

    public function testStartCannotBeAfterEnd()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Meeting cannot end before it starts.');

        $meetingStart = new DateTimeImmutable();
        $meetingEnd = $meetingStart->modify('-1 hour');

        new MeetingDuration($meetingStart, $meetingEnd);
    }
}
