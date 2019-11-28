<?php
declare(strict_types=1);

namespace Procurios\Meeting;

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
        Assert::minCount($programSlots, 1, 'Program must have at least one program slot.');
        $this->preventProgramSlotOverlap($programSlots);
        $this->programSlots = $programSlots;
    }

    public function rescheduleBy(DateInterval $dateInterval): Program
    {
        $rescheduledPrograms = [];
        foreach ($this->programSlots as $programSlot) {
            /** @var ProgramSlot $programSlot */
            $rescheduleSlot = $programSlot->rescheduledBy($dateInterval);
            $rescheduledPrograms[] = $rescheduleSlot;
        }

        return new self($rescheduledPrograms);
    }

    private function preventProgramSlotOverlap(array $programSlots)
    {
        if (count($programSlots) === 1) {
            return;
        }

        /** @var ProgramSlot $comparingSlot*/
        foreach ($programSlots as $comparingSlot) {
            foreach ($programSlots as $comparedSlot) {
                if ($comparingSlot === $comparedSlot) {
                    continue;
                }

                if ($comparingSlot->room !== $comparedSlot->room){
                    continue;
                }

                if ($comparedSlot->start >= $comparingSlot->end) {
                    continue;
                }

                if ($comparedSlot->end <= $comparingSlot->start) {
                    continue;
                }

                throw new DomainException('Slots overlap');
            }
        }
    }
}
