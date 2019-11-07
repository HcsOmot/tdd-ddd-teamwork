<?php

declare(strict_types=1);

namespace Procurios\Meeting\test;

use InvalidArgumentException;
use Procurios\Meeting\Description;

class DescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testThatDescriptionCannotBeCreatedEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Meeting description must not be empty.');
        new Description('');
    }

    public function testThatDescriptionCanBeCreatedWithNonemptyString()
    {
        $descriptionValue = 'This is a silly workshop, don\'t come';
        $description = new Description($descriptionValue);

        $this->assertNotEmpty($description->getDescription());
        $this->assertEquals($descriptionValue, $description->getDescription());
    }
}
