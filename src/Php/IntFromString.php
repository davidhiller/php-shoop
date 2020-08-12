<?php
declare(strict_types=1);

namespace Eightfold\Shoop\Php;

use Eightfold\Foldable\Bend;

class IntFromString extends Bend
{
    public function __invoke(string $payload): int
    {
        return strlen($payload);
    }
}
