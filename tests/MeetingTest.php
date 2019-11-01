<?php
declare(strict_types=1);

namespace Procurios\Meeting\test;

use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;
use Procurios\Meeting\Meeting;
use Procurios\Meeting\Program;
use Procurios\Meeting\ProgramSlot;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class MeetingTest extends TestCase
{
    public function testThatValidMeetingsCanBeInstantiated()
    {
        $this->assertInstanceOf(Meeting::class, new Meeting(
            Uuid::uuid4(),
            'TDD, DDD & Teamwork',
            'This is a silly workshop, don\'t come',
            new DateTimeImmutable('2017-12-15 19:00'),
            new DateTimeImmutable('2017-12-15 21:00'),
            new Program([
                new ProgramSlot(
                    new DateTimeImmutable('2017-12-15 19:00'),
                    new DateTimeImmutable('2017-12-15 20:00'),
                    'Divergence',
                    'Main room'
                ),
                new ProgramSlot(
                    new DateTimeImmutable('2017-12-15 20:00'),
                    new DateTimeImmutable('2017-12-15 21:00'),
                    'Convergence',
                    'Main room'
                ),
            ])
        ));
    }

    public function testThatMeetingCannotBeCreatedWithTitleShorterThanFiveCharacters()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Meeting title must be at least 5 characters long');

        new Meeting(
            Uuid::uuid4(),
            '4 ch',
            'This meeting has a title containing only 4 characters',
            new DateTimeImmutable(),
            DateTimeImmutable::createFromMutable(
                (new DateTime('now'))
                    ->modify('+2 hours')
            ),
            new Program([
                new ProgramSlot(
                    new DateTimeImmutable('2017-12-15 19:00'),
                    new DateTimeImmutable('2017-12-15 20:00'),
                    'Divergence',
                    'Main room'
                ),
                new ProgramSlot(
                    new DateTimeImmutable('2017-12-15 20:00'),
                    new DateTimeImmutable('2017-12-15 21:00'),
                    'Convergence',
                    'Main room'
                ),
            ])
        );
    }

    public function testThatMeetingHasTitleOfAtLeastFiveCharacters()
    {
        $title = '5+ characters meeting title';
        $meeting = new Meeting(
            Uuid::uuid4(),
            $title,
            'This meeting has a title containing more than 5 characters',
            new DateTimeImmutable(),
            DateTimeImmutable::createFromMutable(
                (new DateTime('now'))
                    ->modify('+2 hours')
            ),
            new Program([
                new ProgramSlot(
                    new DateTimeImmutable('2017-12-15 19:00'),
                    new DateTimeImmutable('2017-12-15 20:00'),
                    'Divergence',
                    'Main room'
                ),
                new ProgramSlot(
                    new DateTimeImmutable('2017-12-15 20:00'),
                    new DateTimeImmutable('2017-12-15 21:00'),
                    'Convergence',
                    'Main room'
                ),
            ])
        );

        $this->assertGreaterThanOrEqual(5, strlen($meeting->getTitle()));
        $this->assertEquals($title, $meeting->getTitle());
    }
}
