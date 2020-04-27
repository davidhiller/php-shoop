<?php

namespace Eightfold\Shoop\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\{
    Type,
    ESArray,
    ESBool,
    ESDictionary,
    ESInt,
    ESJson,
    ESObject,
    ESString
};

/**
 * The `ArrayAccess` PHP interface requires the `offsetGet()` method, which allows you to interact with the object using array notation with something like `isset()`.
 *
 * @example $array = Shoop::array([1, 2, 3]); $array[1]; // returns 2
 *
 * @declared Eightfold\Shoop\Traits\Shoop
 *
 * @defined Eightfold\Shoop\Interfaces\ShoopedImp
 *
 * @overridden Eightfold\Shoop\ESBool, Eightfold\Shoop\ESInt, Eightfold\Shoop\ESJson, Eightfold\Shoop\ESObject
 *
 * @return bool
 */
class OffsetSetTest extends TestCase
{
    public function testESArray()
    {
        $this->assertTrue(false);
        $actual = ESArray::fold([false, true])->offsetGet(0);
        $this->assertFalse($actual);
    }

    /**
     * Equivalent to calling `unfold()` regardless of argument value.
     */
    public function testESBool()
    {
        $actual = ESBool::fold(true)->offsetGet(1);
        $this->assertTrue($actual);
    }

    public function testESDictionary()
    {
        $actual = ESDictionary::fold(["key" => false])->offsetGet("key");
        $this->assertFalse($actual);
    }

    /**
     * Equivalent to calling `array()->offsetGet()` on the ESArray.
     */
    public function testESInt()
    {
        $actual = ESInt::fold(10)->offsetExists(8);
        $this->assertTrue($actual);

        $actual = ESInt::fold(10)->offsetExists(10);
        $this->assertTrue($actual);

        $actual = ESInt::fold(10)->offsetExists(11);
        $this->assertFalse($actual);
    }

    /**
     * Equivalent to calling `dictionary()->offsetGet()` on the ESDictionary.
     */
    public function testESJson()
    {
        $actual = ESJson::fold('{"test":true}')->offsetGet("test");
        $this->assertTrue($actual);
    }

    /**
     * Equivalent to calling `dictionary()->offsetGet()` on the ESDictionary.
     */
    public function testESObject()
    {
        $base = new \stdClass();
        $base->test = false;

        $actual = ESObject::fold($base)->offsetGet("test");
        $this->assertFalse($actual);
    }

    public function testESString()
    {
        $actual = ESString::fold("alphabet soup")->offsetGet(10);
        $this->assertEquals("o", $actual);
    }
}
