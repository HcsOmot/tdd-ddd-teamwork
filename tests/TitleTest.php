<?php

declare(strict_types=1);

namespace Procurios\Meeting\test;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Procurios\Meeting\Title;

final class TitleTest extends TestCase
{
    public function testThatTitleMustHaveAtLeastFiveCharacters()
    {
        $this->expectException(InvalidArgumentException::class);
        new Title('four');
    }
}
