<?php
declare(strict_types=1);

namespace Procurios\Meeting;

use DateInterval;
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
        Assert::notEmpty($programSlots, 'At least one ProgramSlot is required.');
        Assert::allIsInstanceOf($programSlots, ProgramSlot::class);
        $this->programSlots = $programSlots;
    }

    /**
     * @param DateTimeImmutable $newMeetingStart
     *
     * @return Program
     */
    public function rescheduledTo(DateTimeImmutable $newMeetingStart): Program
    {
        $rescheduledProgramSlots = [];
        foreach ($this->programSlots as $programSlot) {
            /** @var ProgramSlot $programSlot */
            $rescheduledProgramSlots[] = $programSlot->rescheduledTo($newMeetingStart);
        }

        return new self($rescheduledProgramSlots);
    }

    public function rescheduledBy(DateInterval $diff): Program
    {
        $rescheduledSlots = [];
        foreach ($this->programSlots as $programSlot) {
            $rescheduledSlots[] = $programSlot->rescheduledBy($diff);
        }
        return new self($rescheduledSlots);
    }
}
