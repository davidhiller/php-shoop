<?php

namespace Eightfold\Shoop;

use Eightfold\Shoop\Helpers\{
    Type,
    PhpJson
};

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\Interfaces\{
    Shooped,
    MathOperations,
    Sort,
    Toggle,
    Wrap,
    Drop,
    Has,
    IsIn,
    Each
};

use Eightfold\Shoop\Traits\{
    ShoopedImp,
    MathOperationsImp,
    SortImp,
    ToggleImp,
    WrapImp,
    DropImp,
    HasImp,
    IsInImp,
    EachImp
};

use Eightfold\Shoop\ESDictionary;

class ESJson implements Shooped, MathOperations, Wrap, Drop, Has, IsIn, Each, \JsonSerializable
{
    use ShoopedImp, ToggleImp, MathOperationsImp, SortImp, WrapImp, DropImp, HasImp, IsInImp, EachImp;

    /**
     * @todo Need a solution for the path
     */
    protected $path = "";

    static public function to(ESJson $instance, string $className)
    {
        if ($className === ESArray::class) {
            return PhpJson::toIndexedArray($instance->value());

        } elseif ($className === ESBool::class) {
            return PhpJson::toBool($instance->value());

        } elseif ($className === ESDictionary::class) {
            return PhpJson::toAssociativeArray($instance->value());

        } elseif ($className === ESInt::class) {
            return PhpJson::toInt($instance->value());

        } elseif ($className === ESJson::class) {
            return $instance->value();

        } elseif ($className === ESObject::class) {
            return PhpJson::toObject($instance->value());

        } elseif ($className === ESString::class) {
            return $instance->value();

        }
    }
	public function __construct($initial)
	{
		if (Type::isJson($initial)) {
			$this->value = $initial;

		} else {
			trigger_error("Given string does not appear to be valid JSON: {$initial}", E_USER_ERROR);

		}
	}

    public function jsonSerialize()
    {
        return $this->value;
    }
}
