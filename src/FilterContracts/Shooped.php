<?php

namespace Eightfold\Shoop\FilterContracts;

use Eightfold\Foldable\Foldable;

use Eightfold\Shoop\FilterContracts\Interfaces\Arrayable; // + Associable
use Eightfold\Shoop\FilterContracts\Interfaces\Appendable;
use Eightfold\Shoop\FilterContracts\Interfaces\Comparable;
use Eightfold\Shoop\FilterContracts\Interfaces\Countable;
use Eightfold\Shoop\FilterContracts\Interfaces\Falsifiable;
use Eightfold\Shoop\FilterContracts\Interfaces\Prependable;
use Eightfold\Shoop\FilterContracts\Interfaces\Reversible;
use Eightfold\Shoop\FilterContracts\Interfaces\Stringable;
use Eightfold\Shoop\FilterContracts\Interfaces\Tupleable;

interface Shooped extends
    Foldable,
    Arrayable,
    Appendable,
    Comparable,
    Countable,
    Falsifiable,
    Prependable,
    Reversible,
    Stringable,
    Tupleable
{
    public function __construct($main);
}
