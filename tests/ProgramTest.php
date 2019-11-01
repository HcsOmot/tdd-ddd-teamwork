<?php

declare(strict_types=1);

namespace Procurios\Meeting\test;

use InvalidArgumentException;
use Procurios\Meeting\Program;

class ProgramTest extends \PHPUnit_Framework_TestCase
{
    public function testThatProgramCannotBeCreatedWithoutAnyProgramSlots()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one ProgramSlot is required.');

        new Program([]);
    }
}
