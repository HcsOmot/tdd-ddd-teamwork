<?php

declare(strict_types=1);

namespace Procurios\Meeting;

use Webmozart\Assert\Assert;

class MeetingAndSpecification implements MeetingSpecification
{
    /** @var MeetingSpecification[] $specs */
    private $specs;

    /**
     * MeetingAndSpecification constructor.
     *
     * @param MeetingSpecification[] $specs
     */
    public function __construct(array $specs)
    {
        Assert::allIsInstanceOf($specs, MeetingSpecification::class);
        $this->specs = $specs;
    }

    public function isSatisfiedBy(Meeting $meeting): bool
    {
        foreach ($this->specs as $spec) {
            if (false === $spec->isSatisfiedBy($meeting)) {
                return false;
            }
        }

        return true;
    }
}
