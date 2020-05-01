<?php

declare(strict_types=1);

namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;
use DomainException;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="registrations")
 */
class MeetingRegistration
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var EmailAddress
     * @ORM\Embedded(class="App\Domain\EmailAddress", columnPrefix=false)
     */
    private $attendee;

    /**
     * @var EmailAddress
     * @ORM\Embedded(class="App\Domain\EmailAddress", columnPrefix=false)
     */
    private $plusOne;

    public function __construct(UuidInterface $id, EmailAddress $attendee)
    {
        $this->id = $id;
        $this->attendee = $attendee;
    }

    public function addPlusOne(EmailAddress $otherAttendee): self
    {
        if (null === $this->plusOne) {
            $updatedRegistration = new self(
                $this->id,
                $this->attendee
            );

            $updatedRegistration->plusOne = $otherAttendee;

            return $updatedRegistration;
        }

        throw new DomainException('Cannot add more than 1 PlusOne attendees.');
    }

    public function getEmail(): EmailAddress
    {
        return $this->attendee;
    }

    public function seatsRequired(): int
    {
        return null === $this->plusOne ? 1 : 2;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function removePlusOne(): self
    {
        return new self(
            $this->id,
            $this->attendee
        );
    }
}
