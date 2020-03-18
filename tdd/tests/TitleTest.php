<?php

declare(strict_types=1);

namespace App\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use App\Domain\Title;

/**
 * @coversNothing
 *
 * @small
 */
class TitleTest extends TestCase
{
    public function testThatTitleCannotBeCreatedWithLessThanFiveCharacters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title must have at least five characters');

        new Title('four');
    }
}
