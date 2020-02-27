<?php

declare(strict_types=1);

namespace Procurios\Meeting;

class CreateMeetingCommandHandler
{
    /** @var MeetingRepository */
    private $meetingRepository;

    public function __construct(MeetingRepository $meetingRepository)
    {
        $this->meetingRepository = $meetingRepository;
    }

    public function __invoke(CreateMeetingCommand $command): void
    {
        $meeting = new Meeting(
            $command->getMeetingId(),
            $command->getTitle(),
            $command->getDescription(),
            $command->getDuration(),
            $command->getProgram(),
            $command->getMaxAttendees(),
        );

        $this->meetingRepository->save($meeting);
    }
}