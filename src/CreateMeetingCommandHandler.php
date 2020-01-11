<?php

declare(strict_types=1);

namespace Procurios\Meeting;

class CreateMeetingCommandHandler
{
    public function __invoke(CreateMeetingCommand $command): void
    {
        $meeting = new Meeting(
            $command->getMeetingId(),
            $command->getTitle(),
            $command->getDescription(),
            $command->getDuration(),
            $command->getProgram(),
            $command->getMaxAttendees()
        );
    }
}
