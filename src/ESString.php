<?php

namespace Eightfold\Shoop;

use Eightfold\Shoop\Interfaces\{
    Shooped,
    Countable,
    Toggle,
    Shuffle,
    Wrap,
    Sort,
    Split,
    Has,
    Compare
};

use Eightfold\Shoop\Traits\{
    ShoopedImp,
    CountableImp,
    ToggleImp,
    ShuffleImp,
    WrapImp,
    SortImp,
    HasImp,
    CompareImp
};

use Eightfold\Shoop\Helpers\Type;

class ESString implements
    Shooped,
    Countable,
    Toggle,
    Shuffle,
    Wrap,
    Sort,
    Split,
    Has,
    Compare
{
    use ShoopedImp, CountableImp, ToggleImp, ShuffleImp, WrapImp, SortImp, HasImp, CompareImp;

    public function __construct($string)
    {
        if (is_string($string)) {
            $this->value = $string;

        } elseif (is_a($string, ESString::class)) {
            $this->value = $string->unfold();

        } else {
            $this->value = "";

        }
    }

// - Type Juggling
    public function string(): ESString
    {
        return Shoop::string($this->value);
    }

    public function array(): ESArray
    {
        return Shoop::array(preg_split('//u', $this->value, null, PREG_SPLIT_NO_EMPTY));
    }

    public function dictionary(): ESDictionary
    {
        return $this->array()->dictionary();
    }

    public function json(): ESJson
    {
        return Shoop::json($this->unfold());
    }

// - PHP single-method interfaces
// - Math language
    public function multiply($int)
    {
        $int = Type::sanitizeType($int, ESInt::class)->unfold();
        return Shoop::string(str_repeat($this->unfold(), $int));
    }

    public function plus(...$args)
    {
        $total = $this->value;
        $terms = $args;
        foreach ($terms as $term) {
            $term = Type::sanitizeType($term, ESString::class)->unfold();
            $total .= $term;
        }

        return Shoop::string($total);
    }

    public function minus(...$args): ESString
    {
        $needle = Type::sanitizeType($args[0], ESString::class)->unfold();
        return Shoop::string(str_replace($needle, "", $this->unfold()));
    }

    public function divide($divisor = null, $removeEmpties = true)
    {
        if ($divisor === null) {
            return Shoop::array([]);
        }

        $divisor = Type::sanitizeType($divisor, ESString::class);
        $removeEmpties = Type::sanitizeType($removeEmpties, ESBool::class);

        $exploded = explode($divisor, $this);
        $shooped = Shoop::array($exploded);

        if ($removeEmpties->unfold()) {
            $shooped = $shooped->noEmpties();
        }
        return $shooped;
    }

// - Comparison
// - Manipulate
    public function toggle($preserveMembers = true)
    {
        return $this->array()->toggle()->join("");
    }

    public function sort($caseSensitive = true)
    {
        return $this->array()->sort($caseSensitive)->join("");
    }

    public function start(...$prefixes)
    {
        $combined = implode('', $prefixes);
        return Shoop::string($combined . $this->unfold());
    }

// - Wrap
    public function startsWith($needle): ESBool
    {
        $needle = Type::sanitizeType($needle, ESString::class);
        $substring = substr($this->unfold(), 0, $needle->countUnfolded());
        return Shoop::bool($substring === $needle->unfold());
    }

    public function endsWith($needle): ESBool
    {
        $needle = Type::sanitizeType($needle, ESString::class)->toggle();
        $reversed = $this->toggle();
        return $reversed->startsWith($needle);
    }

// - Split
    public function split($splitter = 1, $splits = 2): ESArray
    {
        $splitter = Type::sanitizeType($splitter, ESString::class)->unfold();
        $splits = Type::sanitizeType($splits, ESInt::class)->unfold();
        return Shoop::array(explode($splitter, $this->unfold(), $splits));
    }

// - Replace
// - Other
    public function lowerFirst(): ESString
    {
        // ?? lower(1, 3, 4) : lower("even") : lower("odd")
        return Shoop::string(lcfirst($this->value));
    }

    public function uppercase(): ESString
    {
        return Shoop::string(strtoupper($this->value));
    }

    public function pathContent()
    {
        if (is_file($this->unfold())) {
            return Shoop::string(file_get_contents($this->unfold()));
        }
        return Shoop::string("");
    }
}
