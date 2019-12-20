<?php
declare(strict_types=1);

namespace Procurios\Meeting\Tests;

use DateTimeImmutable;
use DomainException;
use InvalidArgumentException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Procurios\Meeting\EmailAddress;
use Procurios\Meeting\Meeting;
use Procurios\Meeting\MeetingDuration;
use Procurios\Meeting\MeetingRegistration;
use Procurios\Meeting\Program;
use Procurios\Meeting\ProgramSlot;
use Procurios\Meeting\ProgramSlotDuration;
use Procurios\Meeting\Title;
use Ramsey\Uuid\Uuid;

final class MeetingTest extends TestCase
{
    public function testThatValidMeetingsCanBeInstantiated()
    {
        $this->assertInstanceOf(
            Meeting::class,
            new Meeting(
                Uuid::uuid4(), new Title('TDD, DDD & Teamwork'), 'This is a silly workshop, don\'t come',
                new MeetingDuration(
                    new DateTimeImmutable('2020-01-01 19:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ), new Program([
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
            ]), 10
            )
        );
    }

    public function testThatProgramOnlyAcceptsProgramSlots()
    {
        $this->expectException(InvalidArgumentException::class);
        new Program([new DateTimeImmutable('2020-01-01 19:00')]);
    }

    public function testThatMeetingCannotEndBeforeItStarted()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Meeting cannot end before it has started');

        new Meeting(
            Uuid::uuid4(),
            new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 21:00'),
                new DateTimeImmutable('2020-01-01 19:00')
            ),
            new Program(
                [
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
                    ]
            ),
            10
        );
    }

    public function testThatMeetingMustHaveAtLeastOneProgrammeSlot()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Meeting must have at least one Programme Slot');

        new Meeting(
            Uuid::uuid4(),
            new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ),
            new Program([]),
            10
        );
    }

    public function testThatMeetingCanBeRescheduled()
    {
        $meetingId = Uuid::uuid4();

        $actual = new Meeting(
            $meetingId,
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
                )
                ]
            ),10
        );

        $expected = new Meeting(
            $meetingId, 
            new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-02 12:00'),
                new DateTimeImmutable('2020-01-02 14:00')
            ),
            new Program([
                new ProgramSlot(
                    new ProgramSlotDuration(
                        new DateTimeImmutable('2020-01-02 12:00'),
                        new DateTimeImmutable('2020-01-02 13:00')
                    ),
                'Divergence',
                'Main room'
                )
                ]
            ),
            10
        );

        $rescheduledStart = new DateTimeImmutable('2020-01-02 12:00');
        $actual->rescheduleFor($rescheduledStart);
        $this->assertEquals($expected, $actual);
    }

    public function testThatMultipleProgramSlotsInMeetingWillBeRescheduled()
    {
        $meetingId = Uuid::uuid4();

        $actual = new Meeting(
            $meetingId, new Title('TDD, DDD & Teamwork'), 'This is a silly workshop, don\'t come', new MeetingDuration(
            new DateTimeImmutable('2020-01-01 19:00'),
            new DateTimeImmutable('2020-01-01 21:00')
        ), new Program([
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
                'Divergence',
                'Main room'
            )
        ]), 10
        );

        $expected = new Meeting(
            $meetingId, new Title('TDD, DDD & Teamwork'), 'This is a silly workshop, don\'t come', new MeetingDuration(
            new DateTimeImmutable('2020-01-02 12:00'),
            new DateTimeImmutable('2020-01-02 14:00')
        ), new Program([
            new ProgramSlot(
                new ProgramSlotDuration(
                    new DateTimeImmutable('2020-01-02 12:00'),
                    new DateTimeImmutable('2020-01-02 13:00')
                ),
                'Divergence',
                'Main room'
            ),
            new ProgramSlot(
                new ProgramSlotDuration(
                    new DateTimeImmutable('2020-01-02 13:00'),
                    new DateTimeImmutable('2020-01-02 14:00')
                ),
                'Divergence',
                'Main room'
            )
        ]), 10
        );

        $rescheduledStart = new DateTimeImmutable('2020-01-02 12:00');
        $actual->rescheduleFor($rescheduledStart);
        $this->assertEquals($expected, $actual);
    }

    public function testThatTwoSameProgramSlotsCannotOverlap()
    {
        $this->expectException(DomainException::class);

        new Meeting(
            Uuid::uuid4(), new Title('TDD, DDD & Teamwork'), 'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ), new Program([
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
                    new DateTimeImmutable('2020-01-01 19:00'),
                    new DateTimeImmutable('2020-01-01 20:00')
                ),
                'Convergence',
                'Main room'
            )
        ]), 10
        );
    }

    public function testThatProgramSlotsCannotOverlap()
    {
        $this->expectException(DomainException::class);

        new Meeting(
            Uuid::uuid4(), new Title('TDD, DDD & Teamwork'), 'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ), new Program([
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
                    new DateTimeImmutable('2020-01-01 19:30:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ),
                'Convergence',
                'Main room'
            )
        ]), 10
        );
    }

    public function testThatSameAttendeeCannotRegisterMoreThanOnce(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('User with this email already registered.');
        
        $actual = new Meeting(
            Uuid::uuid4(), new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ), new Program([
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
                    new DateTimeImmutable('2020-01-01 20:30:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ),
                'Convergence',
                'Main room'
            )
        ]),
            10
        );

        $registrationId = Uuid::uuid4();
        $actual->register(new MeetingRegistration(
            $registrationId,
            new EmailAddress('email@domain.tld'))
        );
        $actual->register(new MeetingRegistration(
                $registrationId,
            new EmailAddress('email@domain.tld'))
        );
    }

    public function testThatAttendeeCanRegisterWithPlusOne(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Not enough seats available.');

        $actual = new Meeting(
            Uuid::uuid4(), new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ), new Program([
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
                    new DateTimeImmutable('2020-01-01 20:30:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ),
                'Convergence',
                'Main room'
            )
        ]),
            2
        );

        $registration = new MeetingRegistration(
            Uuid::uuid4(),
            new EmailAddress('primary@attendee.tld')
        );
        $registration = $registration->addPlusOne(new EmailAddress('plus1@attendee.tld'));
        
        $actual->register($registration);
        
        $actual->register(new MeetingRegistration(
            Uuid::uuid4(),
            new EmailAddress('another@attendee.tld'))
        );
    }

    public function testThatAttendeeCanAddTheirPlusOneLaterOn(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Not enough seats available.');

        $actual = new Meeting(
            Uuid::uuid4(), new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ), new Program([
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
                    new DateTimeImmutable('2020-01-01 20:30:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ),
                'Convergence',
                'Main room'
            )
        ]),
            2
        );

        $registration = new MeetingRegistration(
            Uuid::uuid4(),
            new EmailAddress('primary@attendee.tld')
        );

        $actual->register($registration);

//        $registration->addPlusOne(new EmailAddress('plus1@attendee.tld'));

//        $actual->updateRegistration($registration);
        
        $actual->addPlusOne($registration->getId());
        
        $actual->register(
            new MeetingRegistration(
                Uuid::uuid4(),
                new EmailAddress('another@attendee.tld')
            )
        );
    }

    public function testThatRemovingPlusOneEnablesAdditionalRegistration(): void
    {
        $actual = new Meeting(
            Uuid::uuid4(), new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ), new Program([
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
                    new DateTimeImmutable('2020-01-01 20:30:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ),
                'Convergence',
                'Main room'
            )
        ]),
            2
        );

        $registration = new MeetingRegistration(
            Uuid::uuid4(),
            new EmailAddress('primary@attendee.tld')
        );
        $registration = $registration->addPlusOne(new EmailAddress('plus1@attendee.tld'));

        $actual->register($registration);

//        $registration = $registration->removePlusOne();
        
        $actual->removePlusOne($registration->getId());
        
//        $actual->updateRegistration($registration);

        $actual->register(
            new MeetingRegistration(
                Uuid::uuid4(),
                new EmailAddress('another@attendee.tld')
            )
        );

        $this->assertInstanceOf(Meeting::class, $actual);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Not enough seats available.');

        $actual->register(
            new MeetingRegistration(
                Uuid::uuid4(),
                new EmailAddress('notgonnafit@anymore.tld')
            )
        );
    }

    public function testThatAttendeeCanCancelTheirRegistration(): void
    {
        $actual = new Meeting(
            Uuid::uuid4(), new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ), new Program([
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
                    new DateTimeImmutable('2020-01-01 20:30:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ),
                'Convergence',
                'Main room'
            )
        ]),
            2
        );

        $registration = new MeetingRegistration(
            Uuid::uuid4(),
            new EmailAddress('primary@attendee.tld')
        );
        $registration = $registration->addPlusOne(new EmailAddress('plus1@attendee.tld'));

        $actual->register($registration);

        $actual->removeRegistration($registration->getId());

        $actual->register(
            new MeetingRegistration(
                Uuid::uuid4(),
                new EmailAddress('shouldfit@justfine.tld')
            )
        );

        $this->assertInstanceOf(Meeting::class, $actual);
    }

    public function testThatPlusOneIsRemovedFromRegistration(): void
    {
        $actual = new Meeting(
            Uuid::uuid4(), new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ), new Program([
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
                    new DateTimeImmutable('2020-01-01 20:30:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ),
                'Convergence',
                'Main room'
            )
        ]),
            2
        );

        $registration = new MeetingRegistration(
            Uuid::uuid4(),
            new EmailAddress('primary@attendee.tld')
        );
        $registration = $registration->addPlusOne(new EmailAddress('plus1@attendee.tld'));

        $actual->register($registration);

//        TODO: This is causing problems: MeetingRegistration is an immutable VO. The mutation triggered by the
//          Meeting causes a new instance to be returned. This means that we (the calling code) no longer hold a
//          reference to the object actually being used. Our instance of MeetingRegistration is useless at this point.
//          That means we can't operate on it anymore. Does that mean that we shouldn't even be doing this in the
//          first place? Should we have just passed the information into the Meeting object itself?   
        
//        we're operating on object that is representing the city walls - there are narrow gates and passages  
        $actual->removePlusOne($registration->getId());

//        var_dump($registration);
//        $registration->addPlusOne(new EmailAddress('replacement@attendee.tld'));
        
//        TODO: what's the purpose of the MeetingRegistration object, other than having an object wrapping the data?
//          Attendees won't interact with it directly - they'll receive a representation of their registration. It
//          doesn't seem like there's any part of the system we've built so far that benefits with interacting with the
//          MeetingRegistration object directly
        $actual->addPlusOneAttendee($registration->getId(), new EmailAddress('replacement@attendee.tld'));
        $this->assertInstanceOf(Meeting::class, $actual);
    }

    public function testTHATATTENDEECANREGISTERWITHPLUSONEALTERNATIVE(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Not enough seats available.');
// meeting is the aggregate, and at the same time the aggregate root. meeting duration, programslot and program are
// constituents. They don't have meaning on their own, and the aggregate root - the meeting, should be the only way
// of interacting with them.
        $actual = new Meeting(
            Uuid::uuid4(), new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ), new Program([
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
                    new DateTimeImmutable('2020-01-01 20:30:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ),
                'Convergence',
                'Main room'
            )
        ]),
            2
        );

        $primaryAttendee = new EmailAddress('primary@attendee.tld');
        $additionalAttendee = new EmailAddress('plus1@attendee.tld');

        $registrationId = $actual->registerALTERNATIVE($primaryAttendee, $additionalAttendee);

//        TODO: I don't like passing in null values, but the alternative is even worse - defining a default value of
//          null for nullable arguments
        $actual->registerALTERNATIVE(new EmailAddress('no@room.left'), null);
    }

    public function testTHATATTENDEECANCHANGEPLUSONEALTERNATIVE(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('User with this email already registered.');

        $actual = new Meeting(
            Uuid::uuid4(), new Title('TDD, DDD & Teamwork'),
            'This is a silly workshop, don\'t come',
            new MeetingDuration(
                new DateTimeImmutable('2020-01-01 19:00'),
                new DateTimeImmutable('2020-01-01 21:00')
            ), new Program([
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
                    new DateTimeImmutable('2020-01-01 20:30:00'),
                    new DateTimeImmutable('2020-01-01 21:00')
                ),
                'Convergence',
                'Main room'
            )
        ]),
            2
        );

        $primaryAttendee = new EmailAddress('primary@attendee.tld');
        $additionalAttendee = new EmailAddress('plus1@attendee.tld');

        $registrationId = $actual->registerALTERNATIVE($primaryAttendee, $additionalAttendee);

        $actual->replacePlusOne($registrationId, new EmailAddress('i-like-you@better.oh'));
        
        $actual->registerALTERNATIVE(new EmailAddress('i-like-you@better.oh'), null);
    }
}
