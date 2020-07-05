<?php

namespace Eightfold\Shoop\Interfaces;

use Eightfold\Shoop\Interfaces\ShoopedExtensions\{PhpInterfaces, PhpMagicMethods};

use Eightfold\Shoop\{
    ESArray,
    ESInt,
    ESBool,
    ESString,
    ESObject,
    ESDictionary,
    ESJson
};

interface Shooped extends PhpInterfaces, PhpMagicMethods
    // ?? ObjectAccess = __unset, __isset, __get, __set
    // Serializable ??
    // JsonSerializable
{
    static public function fold($args);

    public function unfold();

    public function value();

    public function condition($bool, \Closure $closure = null);

// - Type Juggling
    public function array(): ESArray;

    public function bool(): ESBool;

    public function dictionary(): ESDictionary;

    public function int(): ESInt;

    public function json(): ESJson;

    public function object(): ESObject;

    public function string(): ESString;

// - Getters/Setters
    public function get($member = 0);

    public function getUnfolded($name);

    public function set($value, $member = null, $overwrite = true);
}
