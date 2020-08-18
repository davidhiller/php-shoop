<?php
declare(strict_types=1);

namespace Eightfold\Shoop\PipeFilters\TypeJuggling\AsInteger;

use Eightfold\Foldable\Filter;

/**
 * @deprecated replaced with FromList
 */
class FromArray extends Filter
{
    public function __invoke(array $using): int
    {
        return FromList::apply()->unfoldUsing($using);
    }
}
