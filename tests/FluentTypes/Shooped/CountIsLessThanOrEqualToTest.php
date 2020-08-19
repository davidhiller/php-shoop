<?php

namespace Eightfold\Shoop\Tests\Shooped;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\Shoop;
use Eightfold\Shoop\FluentTypes\ESArray;
use Eightfold\Shoop\FluentTypes\ESBoolean;
use Eightfold\Shoop\FluentTypes\ESDictionary;
use Eightfold\Shoop\FluentTypes\ESInteger;
use Eightfold\Shoop\FluentTypes\ESJson;
use Eightfold\Shoop\FluentTypes\ESObject;
use Eightfold\Shoop\FluentTypes\ESString;

/**
 * @group  CountIsLessThanOrEqualTo
 *
 * The `countIsLessThanOrEqualTo()` method converts the Shoop type using the `count()` method (using the PHP Countable interface) and uses the result to compare the given value to. The result ESBoolean and closure, if available, is then passed to the `isLessThanOrEqualTo()` method.
 *
 * Note: Expects both values to be of the same type, Shoop type, or combination (cannot compare an `int` to a `bool`, for example).
 *
 * @return Eightfold\Shoop\ESBoolean
 */
class CountIsLessThanOrEqualToTest extends TestCase
{
    public function testESArray()
    {
        $base = ["hello", "world"];
        $actual = ESArray::fold($base)->countIsLessThanOrEqualTo(2);
        $this->assertTrue($actual->unfold());
    }

    public function testESBoolean()
    {
        $base = true;
        $actual = ESBoolean::fold($base)->countIsLessThanOrEqualTo(false);
        $this->assertFalse($actual->unfold());
    }

    public function testESDictionary()
    {
        $base = ["member" => "value"];
        $actual = ESDictionary::fold($base)->countIsLessThanOrEqualTo(3);
        $this->assertTrue($actual->unfold());
    }

    public function testESInteger()
    {
        $base = 11;
        $actual = ESInteger::fold(11)->countIsLessThanOrEqualTo(12);
        $this->assertTrue($actual->unfold());
    }

    public function testESJson()
    {
        $base = '{"test":"test"}';
        $actual = ESJson::fold($base)->countIsLessThanOrEqualTo(3);
        $this->assertTrue($actual->unfold());
    }

    public function testESObject()
    {
        $base = new \stdClass();
        $base->test = "test";

        $actual = ESObject::fold($base)->countIsLessThanOrEqualTo(100);
        $this->assertTrue($actual->unfold());
    }

    public function testESString()
    {
        $actual = ESString::fold("a")->countIsLessThanOrEqualTo(4);
        $this->assertTrue($actual->unfold());

        $actual = ESString::fold("b")->countIsLessThanOrEqualTo(1);
        $this->assertTrue($actual->unfold());
    }
}
