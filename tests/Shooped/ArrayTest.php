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
    ESString,
    ESYaml
};

/**
 * The `array()` method typically converts the `Shoop type` to a `PHP indexed array` representation of the original.
 *
 * @return Eightfold\Shoop\ESArray
 */
class ArrayTest extends TestCase
{
    /**
     * @see Eightfold\Shoop\ESArray The original value.
     */
    public function testESArray()
    {
        $expected = [];

        $actual = ESArray::fold([])->array();
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @see PhpBool::toIndexedArray
     */
    public function testESBool()
    {
        $expected = [true];

        $actual = ESBool::fold(true)->array();
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @see PhpAssociativeArray::toIndexedArray
     */
    public function testESDictionary()
    {
        $expected = [];

        $actual = ESDictionary::fold([])->array();
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @see PhpInt::toIndexedArray
     */
    public function testESInt()
    {
        $expected = [0, 1, 2, 3, 4, 5];

        $actual = ESInt::fold(5)->array();
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @see PhpJson::toIndexedArray
     */
    public function testESJson()
    {
        $expected = ["test"];

        $actual = ESJson::fold('{"test":"test"}')->array();
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @see PhpObject::toIndexedArray
     */
    public function testESObject()
    {
        $expected = [];

        $actual = ESObject::fold(new \stdClass())->array();
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @see PhpString::toIndexedArray
     */
    public function testESString()
    {
        $expected = ["h", "e", "l", "l", "o"];

        $actual = ESString::fold("hello")->array();
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @see SymfonyYaml::toIndexedArray
     */
    public function testESYaml()
    {
        $this->assertFalse(false);
        $expected = ["hello" => "world"];
        $actual = ESYaml::fold("hello: world")->array();
    }
}
