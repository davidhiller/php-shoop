<?php

namespace Eightfold\Shoop\Traits;

use Eightfold\Shoop\Helpers\Type;
use Eightfold\Shoop\Helpers\{
    PhpIndexedArray,
    PhpAssociativeArray,
    PhpObject,
    PhpString
};

use Eightfold\Shoop\{
    Interfaces\Foldable,
    Shoop,
    ESArray,
    ESBool,
    ESInt,
    ESString,
    ESObject,
    ESJson,
    ESDictionary
};

trait ToggleImp
{
    public function toggle($preserveMembers = true): Foldable
    {
        if (Type::is($this, ESArray::class)) {
            $array = $this->arrayUnfolded();
            $array = PhpAssociativeArray::reversed($array, $preserveMembers);
            return Shoop::array($array);

        } elseif (Type::is($this, ESBool::class)) {
            $bool = $this->boolUnfolded();
            $bool = ! $bool;
            return Shoop::bool($bool);

        } elseif (Type::is($this, ESDictionary::class)) {
            $array = $this->dictionaryUnfolded();
            $dictionary = PhpAssociativeArray::reversed($array, $preserveMembers);
            return Shoop::dictionary($dictionary);

        } elseif (Type::is($this, ESInt::class)) {
            $int = $this->intUnfolded();
            $int = -1 * $int;
            return Shoop::int($int);

        } elseif (Type::is($this, ESJson::class)) {
            $object = $this->objectUnfolded();
            $object = PhpObject::reversed($object, $preserveMembers);
            $json = PhpObject::toJson($object);
            return Shoop::json($json);

        } elseif (Type::is($this, ESObject::class)) {
            $object = $this->objectUnfolded();
            $object = PhpObject::reversed($object, $preserveMembers);
            return Shoop::object($object);

        } elseif (Type::is($this, ESString::class)) {
            // $string = $this->stringUnfolded();
            // $string = PhpString::reversed($string);
            // return Shoop::string($string);
        }
    }
}
