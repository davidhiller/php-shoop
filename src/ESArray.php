<?php

namespace Eightfold\Shoop;

use Eightfold\Shoop\Traits\Foldable;

class ESArray extends ESBaseType implements
    \Iterator
{
    use Foldable;

    public function __construct($array = [])
    {
        if (is_a($array, ESArray::class)) {
            $array = $array->unfold();
        }
        $this->value = $array;
    }

    public function sorted(): ESArray
    {
        $array = $this->enumerated()->unfold();
        natsort($array);
        return Shoop::array($array)->enumerated();
    }

    public function shuffle(): ESArray
    {
        $array = $this->enumerated()->unfold();
        shuffle($array);
        return Shoop::array($array)->enumerated();
    }

    public function toggle(): ESArray
    {
        return Shoop::array(array_reverse($this->enumerated()->unfold()))->enumerated();
    }

    public function first(bool $makeShoop = true)
    {
        $array = $this->enumerated()->unfold();
        $value = array_shift($array);
        if ($value === null && $makeShoop) {
            return Shoop::array([]);

        } elseif ($value === null) {
            return [];

        } elseif ($makeShoop) {
            return parent::instanceFromValue($value);

        }
        return $value;
    }

    public function last(bool $makeShoop = true)
    {
        return $this->toggle()->first($makeShoop);
    }

    public function dropFirst($length = 1): ESArray
    {

        $length = $this->sanitizeType($length, "int", ESInt::class)->unfold();

        $array = $this->enumerated()->unfold();
        for ($i = 0; $i < $length; $i++) {
            array_shift($array);
        }
        return Shoop::array($array)->enumerated();
    }

    public function dropLast($length = 1): ESArray
    {
        return $this->enumerated()->toggle()->dropFirst($length)->toggle()->enumerated();
    }

    public function removeEmptyValues(): ESArray
    {
        return Shoop::array(array_filter($this->unfold()))->enumerated();
    }

    public function enumerated()
    {
        return Shoop::array(array_values($this->value));
    }

    public function plus($values)
    {
        $values = $this->sanitizeType($values, "array", ESArray::class)
            ->unfold();
        return Shoop::array(array_merge($this->unfold(), $values));
    }

    public function minus($values): ESArray
    {
        if (static::valueIsNotArray($values)) {
            $values = [$values];
        }
        $deletes = $this->sanitizeType($values, "array", ESArray::class)->unfold();
        $copy = $this->unfold();
        for ($i = 0; $i < count($this->unfold()); $i++) {
            foreach ($deletes as $check) {
                if ($check === $copy[$i]) {
                    unset($copy[$i]);
                }
            }
        }
        return Shoop::array(array_values($copy));
    }

    public function dividedBy($divisor): ESTuple
    {
        $divisor = $this->sanitizeType(
                $divisor,
                "int",
                ESInt::class
            )->unfold();

        $left = array_slice($this->unfold(), 0, $divisor);
        $right = array_slice($this->unfold(), $divisor);

        return Shoop::dictionary(["lhs" => $left, "rhs" => $right]);
    }

    public function join($delimiter = ""): ESString
    {
        $delimiter = $this->sanitizeType(
                $delimiter,
                "string",
                ESString::class
            )->unfold();
        return Shoop::string(implode($delimiter, $this->unfold()));
    }

    public function removeAtIndex($int): ESArray
    {
        $int = $this->sanitizeType(
                $int,
                "int",
                ESInt::class
            )->unfold();
        $array = $this->unfold();
        unset($array[$int]);
        return Shoop::array($array)->enumerated();
    }

    public function insertAtIndex($value, $int): ESArray
    {
        $int = $this->sanitizeType($int, "int", ESInt::class)->unfold();
        $value = $this->sanitizeType($value, "array", ESArray::class)->unfold();

        $lhs = array_slice($this->unfold(), 0, $int);
        $rhs = array_slice($this->unfold(), $int);
        $merged = array_merge($lhs, $value, $rhs);
        return Shoop::array($merged)->enumerated();
    }

    public function hasValue($value): ESBool
    {
        return Shoop::bool(in_array($value, $this->value));
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

//-> Iterator
    public function current(): ESInt
    {
        $current = key($this->value);
        return Shoop::int($this->value[$current]);
    }

    public function key(): ESInt
    {
        return Shoop::int(key($this->value));
    }

    public function next(): ESArray
    {
        next($this->value);
        return $this;
    }

    public function rewind(): ESArray
    {
        reset($this->value);
        return $this;
    }

    /**
     * @return bool Must be bool for sake of PHP
     */
    public function valid(): bool
    {
        $key = key($this->value);
        $var = ($key !== null && $key !== false);
        return $var;
    }
}
