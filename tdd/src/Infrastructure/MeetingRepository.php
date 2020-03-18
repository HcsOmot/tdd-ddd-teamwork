<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\MeetingSpecification;
use App\Domain\Meeting;
use Ramsey\Uuid\UuidInterface;

interface MeetingRepository
{
    public function getMeeting(UuidInterface $meetingId): Meeting;

    public function save(Meeting $meeting): void;

    public function findBySpec(MeetingSpecification $spec): array;
}
