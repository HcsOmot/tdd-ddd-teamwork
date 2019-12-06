<?php
declare(strict_types=1);

namespace Procurios\Meeting;

use DateTimeImmutable;
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
        $this->programSlots = $programSlots;
    }

    public function rescheduleFor(DateTimeImmutable $newStart): Program
    {
        $rescheduledPrograms = [];
        foreach ($this->programSlots as $programSlot) {
            /** @var ProgramSlot $programSlot */
            $rescheduledPrograms[] = $programSlot->rescheduleFor($newStart);
        }
        return new self($rescheduledPrograms);
    }
}
