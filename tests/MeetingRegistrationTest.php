<?php

declare(strict_types=1);

namespace Procurios\Meeting\Tests;

use DomainException;
use PHPUnit\Framework\TestCase;
use Procurios\Meeting\EmailAddress;
use Procurios\Meeting\MeetingRegistration;
use Ramsey\Uuid\Uuid;

/**
 * @coversNothing
 *
 * @small
 */
class MeetingRegistrationTest extends TestCase
{
    public function testThatRegistrationCanBeExtendedWithOneAttendee(): void
    {
        $this->expectException(DomainException::class);

        $actual = new MeetingRegistration(
            Uuid::uuid4(),
            new EmailAddress('primary@attendee.tld')
        );

        $actual = $actual->addPlusOne(new EmailAddress('plus1@attendee.tld'));

        $actual->addPlusOne(new EmailAddress('plus1@attendee.tld'));
    }

    public function testThatRegistrationKeepsCountOfSeatsRequired(): void
    {
        $actual = new MeetingRegistration(
            Uuid::uuid4(),
            new EmailAddress('primary@attendee.tld')
        );

        $actual = $actual->addPlusOne(new EmailAddress('plus1@attendee.tld'));

        static::assertEquals(2, $actual->seatsRequired());

        $actual = $actual->removePlusOne();

        static::assertEquals(1, $actual->seatsRequired());
    }
}
