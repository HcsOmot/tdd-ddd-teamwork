<?php

declare(strict_types=1);

namespace Procurios\Meeting\test;

use InvalidArgumentException;
use Procurios\Meeting\Title;

class TitleTest extends \PHPUnit_Framework_TestCase
{
    public function testThatTitleCannotBeInstantiatedWithLessThanFiveCharacters()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Meeting title must be at least 5 characters long');
        new Title('4 ch');
    }

    public function testThatTitleHasAtLeastFiveCharacters()
    {
        $titleValue = '5+ characters meeting title';
        $title = new Title($titleValue);

        $this->assertGreaterThanOrEqual(5, strlen($title->getTitle()));
        $this->assertEquals($titleValue, $title->getTitle());
    }
}
