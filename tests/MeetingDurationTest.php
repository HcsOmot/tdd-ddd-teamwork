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
        $start = new DateTimeImmutable();
        $end = $start->modify('+1 hour');

        $sut = new MeetingDuration($start, $end);

        $this->assertInstanceOf(MeetingDuration::class, $sut);

        $this->assertNotNull($sut->getStart());
        $this->assertNotNull($sut->getEnd());
    }

    public function testStartCannotBeAfterEnd()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Meeting cannot start after it ends.');

        $end = new DateTimeImmutable();
        $start = $end->modify('+1 day');

        new MeetingDuration($start, $end);
    }
}
