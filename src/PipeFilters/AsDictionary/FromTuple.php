<?php
declare(strict_types=1);

namespace Eightfold\Shoop\PipeFilters\AsDictionary;

use Eightfold\Foldable\Filter;

use Eightfold\Shoop\PipeFilters\IsObject;
use Eightfold\Shoop\PipeFilters\AsTuple\FromObject;

class FromTuple extends Filter
{
    public function __invoke(object $using): array
    {
        if (IsObject::apply()->unfoldUsing($using)) {
            $using = FromObject::apply()->unfoldUsing($using);

        }
        return (array) $using;
    }
}
