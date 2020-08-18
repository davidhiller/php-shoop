<?php
declare(strict_types=1);

namespace Eightfold\Shoop\PipeFilters\TypeJuggling;

use Eightfold\Foldable\Filter;

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\PipeFilters\TypeJuggling\IsJson;
use Eightfold\Shoop\PipeFilters\TypeJuggling\AsString\FromList;
use Eightfold\Shoop\PipeFilters\TypeJuggling\AsString\FromBool;
use Eightfold\Shoop\PipeFilters\TypeJuggling\AsString\FromInteger;

// TODO: Update using pattern from Span
class AsString extends Filter
{
    private $glue = "";

    public function __construct(string $glue = "")
    {
        $this->glue = $glue;
    }

    public function __invoke($using): string
    {
        if (is_bool($using)) {
            return Shoop::pipe($using, FromBool::apply())->unfold();

        } elseif (is_int($using)) {
            return Shoop::pipe($using, FromInteger::apply())->unfold();

        } elseif (is_object($using)) {
            return Shoop::pipe($using,
                AsArrayOfStrings::apply(),
                AsString::applyWith($this->glue)
            )->unfold();

        if (method_exists($using, "__toString")) {
            return (string) $using;
        }

        return Shoop::pipe($using,
            AsDictionary::apply($using),
            AsString::apply($array)
        )->unfold();

        } elseif (is_array($using)) {
            return FromList::applyWith($this->glue)->unfoldUsing($using);

        } elseif (is_string($using)) {
            $isJson = Shoop::pipe($using, StringIsJson::apply())->unfold();
            if ($isJson) {
var_dump(__FILE__);
var_dump(__LINE__);
var_dump($using);
                // return Shoop::pipe($using, ToArrayFromJson::apply())
                    // ->unfold();
            }
            return $using;
        }
var_dump(__FILE__);
var_dump($using);
        return "";
    }
}