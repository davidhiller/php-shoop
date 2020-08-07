<?php

namespace Eightfold\Shoop\Interfaces;

// use Eightfold\Shoop\Interfaces\ShoopedExtensions\{
//     PhpInterfaces,
//     PhpMagicMethods
// };

use Eightfold\Shoop\Interfaces\Foldable;
use Eightfold\Shoop\Interfaces\ShoopedExtensions\PhpInterfaces;

use Eightfold\Shoop\{
    ESArray,
    ESInt,
    ESBool,
    ESString,
    ESObject,
    ESDictionary,
    ESJson
};

interface Shooped extends Foldable, PhpInterfaces
{
    public function array(): ESArray;

    public function bool(): ESBool;

    public function dictionary(): ESDictionary;

    public function int(): ESInt;

    public function json(): ESJson;

    public function object(): ESObject;

    public function string(): ESString;
}
