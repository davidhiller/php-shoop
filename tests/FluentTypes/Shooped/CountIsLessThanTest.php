<?php

namespace Eightfold\Shoop\Tests\Shooped;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\Helpers\Type;

use Eightfold\Shoop\{
    Shoop,
    ESArray,
    ESBool,
    ESDictionary,
    ESInt,
    ESJson,
    ESObject,
    ESString
};

/**
 * The `countIsLessThan()` method converts the Shoop type using the `count()` method (using the PHP Countable interface) and uses the result to compare the given value to. The result ESBool and closure, if available, is then passed to the `isLessThan()` method.
 *
 * Note: Expects both values to be of the same type, Shoop type, or combination (cannot compare an `int` to a `bool`, for example).
 *
 * @return Eightfold\Shoop\ESBool
 */
class CountIsLessThanTest extends TestCase
{
    public function testESArray()
    {
        $base = ["hello", "world"];
        $actual = ESArray::fold($base)->countIsLessThan(3);
        $this->assertTrue($actual->unfold());

        $actual = ESArray::fold($base)->countIsLessThan(3, function($result, $array) {
            return $array;
        });
        $this->assertTrue(is_a($actual, ESArray::class));
        $this->assertEquals($base, $actual->unfold());
    }

    public function testESBool()
    {
        $base = true;
        $actual = ESBool::fold($base)->countIsLessThan(false);
        $this->assertFalse($actual->unfold());
    }

    public function testESDictionary()
    {
        $base = ["member" => "value"];
        $actual = ESDictionary::fold($base)->countIsLessThan(1);
        $this->assertFalse($actual->unfold());
    }

    public function testESInt()
    {
        $base = 11;
        $actual = ESInt::fold(11)->countIsLessThan(9);
        $this->assertFalse($actual->unfold());
    }

    public function testESJson()
    {
        $base = '{"test":"test"}';
        $actual = ESJson::fold($base)->countIsLessThan(1);
        $this->assertFalse($actual->unfold());
    }

    public function testESObject()
    {
        $base = new \stdClass();
        $base->test = "test";

        $actual = ESObject::fold($base)->countIsLessThan(1);
        $this->assertFalse($actual->unfold());
    }

    public function testESString()
    {
        $actual = ESString::fold("a")->countIsLessThan(3);
        $this->assertTrue($actual->unfold());

        $actual = ESString::fold("b")->countIsLessThan(1);
        $this->assertFalse($actual->unfold());
    }
}