<?php

namespace Eightfold\Shoop\Interfaces\ShoopedExtensions;

use \Countable;
use \ArrayAccess;
use \Iterator;
use \JsonSerializable;

interface PhpInterfaces extends Countable, ArrayAccess, Iterator, JsonSerializable
{
// - Countable
    public function count();

// - Array Access
    public function offsetExists($offset): bool;

    public function offsetGet($offset);

    public function offsetSet($offset, $value): void;

    public function offsetUnset($offset): void;

// - Iterator
    public function rewind(): void;

    public function valid(): bool;

    public function current();

    public function key();

    public function next(): void;

// - JsonSerializable
    public function jsonSerialize();
}