<?php

namespace Eightfold\Shoop;

use Eightfold\Shoop\Helpers\Type;

use Eightfold\Shoop\Interfaces\{
    Shooped,
    Compare,
    MathOperations,
    Toggle,
    Shuffle,
    Wrap,
    Sort,
    Drop,
    Has
};

use Eightfold\Shoop\Traits\{
    ShoopedImp,
    CompareImp,
    MathOperationsImp,
    ToggleImp,
    ShuffleImp,
    WrapImp,
    SortImp,
    DropImp,
    HasImp
};

class ESArray implements
    Shooped,
    Compare,
    MathOperations,
    Toggle,
    Shuffle,
    Wrap,
    Sort,
    Drop,
    Has
{
    use ShoopedImp, CompareImp, MathOperationsImp, ToggleImp, ShuffleImp, WrapImp, SortImp, DropImp, HasImp;

    public function __construct($array = [])
    {
        if (is_a($array, ESArray::class)) {
            $array = $array->unfold();

        } elseif (! is_array($array)) {
            $array = [$array];

        }
        $this->value = $array;
    }

// - Type Juggling
// - PHP single-method interfaces
// - Manipulate
// - Wrap
// - Math language
    public function split($splitter = 1, $splits = 2)
    {
        return $this->divide($splitter);
    }

// - Comparison
// - Getters
// - Other
    public function join($delimiter = ""): ESString
    {
        $delimiter = Type::sanitizeType($delimiter, ESString::class);
        return Shoop::string(implode($delimiter->unfold(), $this->unfold()));
    }

    public function insertAt($value, $int)
    {
        // TODO: Consider making plus an alias of this
        $int = Type::sanitizeType($int, ESInt::class)->unfold();
        $value = Type::sanitizeType($value, ESArray::class)->unfold();

        $lhs = array_slice($this->unfold(), 0, $int);
        $rhs = array_slice($this->unfold(), $int);

        $merged = array_merge($lhs, $value, $rhs);
        return Shoop::array($merged)->array();
    }

    public function each(\Closure $closure): ESArray
    {
        $build = [];
        foreach ($this->value as $key => $value) {
            $consider = $closure($value, $key = "");
            if ($consider !== null) {
                $build[] = $consider;
            }
        }
        return Shoop::array($build);
    }

    public function summed()
    {
        $total = 0;
        foreach ($this->unfold() as $int) {
            $total += Type::sanitizeType($int, ESInt::class)->unfold();
        }
        return Shoop::int($total);
    }

//-> Getters
    // private function knownMethodFromUnknownName(string $name)
    // {
    //     $call = "";
    //     $start = strlen($name) - strlen("Unfolded");
    //     $isFolded = $this->methodNameContains("Unfolded", $name, $start);
    //     if ($isFolded) {
    //         $call = lcfirst(substr_replace($name, "", $start, strlen($name) - $start));
    //     }

    //     if (strlen($call) === 0) {
    //         $className = static::class;
    //         trigger_error("{$name} is an invalid method on {$className}", E_USER_ERROR);
    //     }
    //     return $call;
    // }

    // private function methodNameContains(string $needle, string $haystack, int $start)
    // {
    //     $needle = $needle;
    //     $end = strlen($haystack);
    //     $len = strlen($needle);
    //     return substr($haystack, $start, $len) === $needle;
    // }
}
