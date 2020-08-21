<?php
declare(strict_types=1);

namespace Eightfold\Shoop\PipeFilters;

use Eightfold\Foldable\Filter;

class TypeAsBoolean extends Filter
{
    public function __invoke($using): bool
    {
        if (TypeIs::applyWith("boolean")->unfoldUsing($using)) {
            return $using;

        } elseif (TypeIs::applyWith("number")->unfoldUsing($using)) {
            return (bool) $using;

        } else {
            $int = TypeAsInteger::apply()->unfoldUsing($using);
            return TypeAsBoolean::apply()->unfoldUsing($int);

        }
    }
}