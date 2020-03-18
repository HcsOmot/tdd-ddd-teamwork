<?php

declare(strict_types=1);

namespace App\Domain;

use DateInterval;
use DomainException;
use Webmozart\Assert\Assert;

final class Program
{
    /** @var ProgramSlot[] */
    private $programSlots;

    /**
     * @param ProgramSlot[] $programSlots
     */
    public function __construct(array $programSlots)
    {
        Assert::allIsInstanceOf($programSlots, ProgramSlot::class);
        Assert::minCount($programSlots, 1, 'Meeting must have at least one Programme Slot');
        $this->preventProgramSlotOverlap($programSlots);
        $this->programSlots = $programSlots;
    }

    public function rescheduleFor(DateInterval $offset): self
    {
        $rescheduledPrograms = [];
        foreach ($this->programSlots as $programSlot) {
            $rescheduledPrograms[] = $programSlot->rescheduleBy($offset);
        }

        return new self($rescheduledPrograms);
    }

    private function preventProgramSlotOverlap(array $programSlots): void
    {
        /** @var ProgramSlot[] $programSlots */
        foreach ($programSlots as $current) {
            /** @var ProgramSlot[] $programSlots */
            foreach ($programSlots as $compared) {
                if ($current->overlapsWith($compared)) {
                    throw new DomainException();
                }
            }
        }
    }
}