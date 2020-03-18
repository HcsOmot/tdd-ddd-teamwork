<?php
declare(strict_types=1);

namespace App\Infrastructure;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Domain\Meeting;

class DoctrineMeetingRepository  extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meeting::class);
    }

    public function save(Meeting $meeting)
    {
        $this->_em->persist($meeting);
        $this->_em->flush();
    }
}