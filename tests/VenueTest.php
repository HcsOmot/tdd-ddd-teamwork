<?php

declare(strict_types=1);

namespace Procurios\Meeting\Tests;

use DateTimeImmutable;
use DomainException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Procurios\Meeting\Meeting;
use Procurios\Meeting\MeetingDuration;
use Procurios\Meeting\Program;
use Procurios\Meeting\ProgramSlot;
use Procurios\Meeting\ProgramSlotDuration;
use Procurios\Meeting\Room;
use Procurios\Meeting\Title;
use Procurios\Meeting\Venue;
use Ramsey\Uuid\Uuid;

/**
 * @coversNothing
 *
 * @small
 */
class VenueTest extends TestCase
{
    public function testThatVenueCanBeCreated(): void
    {
        static::assertInstanceOf(
            Venue::class,
            new Venue(
                Uuid::uuid4(),
                'City Plaza Zagreb'
            )
        );
    }

    public function testThatVenueCanBeBookedForSomeTime(): void
    {
        $this->expectException(DomainException::class);
        
        $actual = new Venue(
            Uuid::uuid4(),
            'City Plaza Zagreb');

        $meeting = new Meeting(
            Uuid::uuid4(),
            new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ),
            new Program([
                new ProgramSlot(
                    new ProgramSlotDuration(
                        new DateTimeImmutable('2020-01-01 19:00'),
                        new DateTimeImmutable('2020-01-01 20:00')
                    ),
                    'Divergence',
                    'Main room'
                ),
                new ProgramSlot(
                    new ProgramSlotDuration(
                        new DateTimeImmutable('2020-01-01 20:00'),
                        new DateTimeImmutable('2020-01-01 21:00')
                    ),
                    'Convergence',
                    'Main room'
                ),
            ]),
            10
        );
        $actual->bookFor(
            $meeting->getDuration()
        );
        
        $actual->bookFor($meeting->getDuration());
    }
}
