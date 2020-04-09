<?php
declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\MeetingSpecification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Domain\Meeting;
use Ramsey\Uuid\UuidInterface;

class DoctrineMeetingRepository  extends ServiceEntityRepository implements MeetingRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meeting::class);
    }

    public function save(Meeting $meeting): void
    {
        $this->_em->persist($meeting);
        $this->_em->flush();
    }

    public function getMeeting(UuidInterface $meetingId): Meeting
    {
        // TODO: Implement getMeeting() method.
    }

    public function findBySpec(MeetingSpecification $spec): array
    {
        // TODO: Implement findBySpec() method.
    }
}