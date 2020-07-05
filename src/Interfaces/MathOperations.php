<?php

namespace Eightfold\Shoop\Interfaces;

use Eightfold\Shoop\ESInt;

interface MathOperations
{
    public function plus(...$args);

    public function minus(...$args);

    public function multiply($int);

    public function divide($divisor = 0, $includeEmpties = true, $limit = PHP_INT_MAX);
}
