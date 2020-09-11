<?php
declare(strict_types=1);

namespace Eightfold\Shoop\Filter\TypeFilters;

use Eightfold\Foldable\Filter;

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\Filter\Type;
use Eightfold\Shoop\Filter\RetainUsing;

use Eightfold\Shoop\FilterContracts\Interfaces\Stringable;

class AsString extends Filter
{
    public function __invoke($using): string
    {
        if (Type::isBoolean()->unfoldUsing($using)) {
            return static::fromBoolean($using);

        } elseif (Type::isNumber()->unfoldUsing($using)) {
            return static::fromNumber($using);

        } elseif (Type::isString()->unfoldUsing($using)) {
            return static::fromString($using);

        } elseif (Type::isList()->unfoldUsing($using)) {
            return static::fromList($using);

        } elseif (Type::isTuple()->unfoldUsing($using)) {
            return static::fromTuple($using);

        } elseif (Type::isObject()->unfoldUsing($using)) {
            return static::fromObject($using);

        }
    }

    static public function fromBoolean(bool $using): string
    {
        return ($using) ? "true" : "false";
    }

    // TODO: PHP 8 - int|float
    /**
     * Convert a given number to a string representation of that number.
     *
     * @param  int|integer|float|double $using             The number to process.
     * @param  int|integer              $decimalPlaces     Number of decimal places to force. Default is 1 to circumvent dynamic typing back to an integer or float when used as members of dictionaries and tuples.
     * @param  string                   $decimalPoint      The character to separate whole numbers and decimal values.
     * @param  string                   $thousandSeparator The character to separate counts of one-thousand.
     * @return string                                      A string in the specified format.
     */
    static public function fromNumber(
        $using,
        int $decimalPlaces        = 1,
        string $decimalPoint      = ".",
        string $thousandSeparator = ","
    ): string
    {
        return number_format(
            $using,
            $decimalPlaces,
            $decimalPoint,
            $thousandSeparator
        );
    }

    static public function fromString(string $using): string
    {
        return $using;
    }

    static public function fromList(array $using, string $glue = ""): string
    {
        $array = RetainUsing::fromList($using, "is_string");
        return implode($glue, $array);
    }

    static public function fromTuple($using): string
    {
        $dictionary = AsDictionary::fromTuple($using);
        return static::fromList($dictionary);
    }

    static public function fromJson(string $using): string
    {
        $tuple = AsDictionary::fromJson($using);
        return static::fromList($tuple);
    }

    static public function fromObject(object $using): string
    {
        return (is_a($using, Stringable::class))
            ? $using->efToString()
            : static::fromTuple($using);
    }
}