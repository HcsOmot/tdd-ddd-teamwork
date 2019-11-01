<?php
declare(strict_types=1);

namespace Procurios\Meeting;

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
}
