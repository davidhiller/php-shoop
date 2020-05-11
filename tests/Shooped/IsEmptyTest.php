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
 * @see Type::isEmpty
 */
class IsEmptyTest extends TestCase
{
    public function testESArray()
    {
        $base = ["hello", "world"];
        $actual = ESArray::fold($base)->isEmpty($base);
        $this->assertFalse($actual->unfold());

        $base = [];
        $actual = ESArray::fold($base)->isEmpty($base);
        $this->assertTrue($actual->unfold());
    }

    public function testESBool()
    {
        $base = true;
        $actual = ESBool::fold($base)->isEmpty();
        $this->assertFalse($actual->unfold());

        $base = false;
        $actual = ESBool::fold($base)->isEmpty();
        $this->assertTrue($actual->unfold());
    }

    public function testESDictionary()
    {
        $base = ["key" => "value"];
        $actual = ESDictionary::fold($base)->isEmpty();
        $this->assertFalse($actual->unfold());
    }

    public function testESInt()
    {
        $base = 0;
        $actual = ESInt::fold($base)->isEmpty();
        $this->assertTrue($actual->unfold());

        $base = 10;
        $actual = ESInt::fold($base)->isEmpty();
        $this->assertFalse($actual->unfold());

        $base = -1;
        $actual = ESInt::fold($base)->isEmpty();
        $this->assertFalse($actual->unfold());
    }

    /**
     * Uses `object()` then checks if the ESObject `isEmpty()` (no members).
     */
    public function testESJson()
    {
        $base = '{}';
        $actual = ESJson::fold($base)->isEmpty();
        $this->assertTrue($actual->unfold());

        $base = '{"test":"test"}';
        $actual = ESJson::fold($base)->isEmpty();
        $this->assertFalse($actual->unfold());
    }

    /**
     * Uses `dictionary()` then checks if the ESDictionary `isEmpty()`
     */
    public function testESObject()
    {
        $base = new \stdClass();
        $base->test = "test";
        $actual = ESObject::fold($base)->isEmpty();
        $this->assertFalse($actual->unfold());
    }

    public function testESString()
    {
        $base = "alphabet soup";
        $actual = ESString::fold($base)->isEmpty();
        $this->assertFalse($actual->unfold());

        $base = "";
        $actual = ESString::fold($base)->isEmpty();
        $this->assertTrue($actual->unfold());
    }
}
