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
        Assert::allIsInstanceOf($programSlots, ProgramSlot::class);
        Assert::minCount($programSlots, 1, 'Program must have at least one program slot.');
        $this->programSlots = $programSlots;
    }
}
