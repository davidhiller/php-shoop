<?php

namespace Eightfold\Shoop\Traits;

use \Closure;

use Eightfold\Shoop\Helpers\{
    Type,
    PhpAssociativeArray,
    PhpBool,
    PhpIndexedArray,
    PhpJson,
    PhpObject,
    PhpString
};

use Eightfold\Shoop\Helpers\PhpTypeJuggle;

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

trait HasImp
{
    public function has($needle, Closure $closure = null): Foldable
    {
        $array = $this->arrayUnfolded();
        $bool = in_array($needle, $array);
        return Shoop::this($this->condition($bool, $closure));
    }

    public function doesNotHave($needle, Closure $closure = null): Foldable
    {
        return $this->has($needle, $closure)->toggle();
    }

    public function hasMember($member, Closure $closure = null): Foldable
    {
        if (Type::is($this, ESDictionary::class, ESJson::class, ESObject::class)) {
            $member = Type::sanitizeType($member, ESString::class)->unfold();

        } else {
            $member = Type::sanitizeType($member, ESInt::class)->unfold();

        }

        $value = $this->arrayUnfolded();
        $class = PhpIndexedArray::class;
        if (Type::is($this, ESDictionary::class)) {
            $value = $this->dictionaryUnfolded();
            $class = PhpAssociativeArray::class;

        } elseif (Type::is($this, ESJson::class)) {
            $value = $this->jsonUnfolded();
            $class = PhpJson::class;

        } elseif (Type::is($this, ESObject::class)) {
            $value = $this->objectUnfolded();
            $class = PhpObject::class;

        } elseif (Type::is($this, ESString::class)) {
            $value = $this->stringUnfolded();
            $class = PhpString::class;

        }
        $bool = $class::hasMember($value, $member);
        return Shoop::this($this->condition($bool, $closure));
    }

    public function doesNotHaveMember($member, Closure $closure = null): Foldable
    {
        return $this->hasMember($member, $closure)->toggle();
    }
}
