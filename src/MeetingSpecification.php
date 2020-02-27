<?php

declare(strict_types=1);

namespace Procurios\Meeting;

interface MeetingSpecification
{
    public function isSatisfiedBy(Meeting $meeting): bool;
}
