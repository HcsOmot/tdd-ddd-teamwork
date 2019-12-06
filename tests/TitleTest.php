<?php

declare(strict_types=1);

namespace Procurios\Meeting\Tests;

use InvalidArgumentException;
use Procurios\Meeting\Title;
use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase
{

    public function testThatTitleCannotBeCreatedWithLessThanFiveCharacters()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title must have at least five characters');

        new Title('four');
    }
}
