<?php

declare(strict_types=1);

namespace Procurios\Meeting\test;

use Procurios\Meeting\MeetingId;
use Ramsey\Uuid\Uuid;

class MeetingIdTest extends \PHPUnit_Framework_TestCase
{
    public function testThatItCanBeCreatedFromUuid()
    {
        $uuid = Uuid::uuid4();

        $meetingId = new MeetingId($uuid);

        $this->assertInstanceOf(MeetingId::class, $meetingId);
    }

    public function testThatItReturnsValidIdValue()
    {
        $uuid = Uuid::uuid4();

        $meetingId = new MeetingId($uuid);

        $this->assertEquals($uuid->toString(), $meetingId->getId());
    }
}
