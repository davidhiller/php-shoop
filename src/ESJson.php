<?php

namespace Eightfold\Shoop;

use Eightfold\Shoop\Helpers\Type;

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\Interfaces\{
    Shooped,
    Compare,
    MathOperations,
    Sort,
    Toggle,
    Has
};

use Eightfold\Shoop\Traits\{
    ShoopedImp,
    CompareImp,
    MathOperationsImp,
    SortImp,
    ToggleImp,
    HasImp
};

use Eightfold\Shoop\ESDictionary;

// TODO: Need to be able to handle the path
class ESJson implements Shooped, Compare, MathOperations, Has, \JsonSerializable
{
    use ShoopedImp, CompareImp, ToggleImp, MathOperationsImp, SortImp, HasImp;

    // TODO: How to store path ??
    protected $path = "";

// Start here
	public function __construct($initial)
	{
		if (Type::isJson($initial)) {
			$this->value = $initial;

		} else {
			trigger_error("Given string does not appear to be valid JSON: {$initial}", E_USER_ERROR);

		}
	}

// - Type Juggling
// - Comparison
    public function isEmpty(): ESBool
    {
        return $this->object()->isEmpty();
    }

// - PHP single-method interfaces
    public function jsonSerialize()
    {
        return $this->value;
    }

// - Math language
// - Other
    public function set($value, $key, $overwrite = true)
    {
        $key = Type::sanitizeType($key, ESString::class)->unfold();
        $overwrite = Type::sanitizeType($overwrite, ESBool::class)->unfold();

        $cast = json_decode($this->value);
        if (! $overwrite && $this->hasMember($key)) {
            $currentValue = $cast->{$key};
            if (is_array($currentValue)) {
                $currentValue->{$key} = $value;

            } else {
                $currentValue = [$currentValue, $value];

            }

            $cast->{$key} = $currentValue;
            return static::fold($cast);
        }

        $cast->{$key} = $value;
        $encoded = json_encode($cast);
        return static::fold($encoded);
    }

    public function hasMember($member): ESBool
    {
        $v = (array) json_decode($this->unfold());
        return Shoop::bool(array_key_exists($member, $v) || (is_int($member) && count($v) > $member));
    }

    public function path(): ESString
    {
        return Shoop::string($this->path);
    }

    public function setPath($path): ESJson
    {
        $path = Type::sanitizeType($path, ESString::class)->unfold();
        $this->path = $path;
        return $this;
    }

// -> Array Access
}
