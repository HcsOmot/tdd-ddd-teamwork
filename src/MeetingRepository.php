<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use Ramsey\Uuid\UuidInterface;

interface MeetingRepository
{
    public function getMeeting(UuidInterface $meetingId): Meeting;

    public function save(Meeting $meeting): void;

    public function findBySpec(MeetingSpecification $spec): array;
}
