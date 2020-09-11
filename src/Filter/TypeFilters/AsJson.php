<?php
declare(strict_types=1);

namespace Eightfold\Shoop\Filter\TypeFilters;

use Eightfold\Foldable\Filter;

use Eightfold\Shoop\Filter\Type;

use Eightfold\Shoop\FilterContracts\Interfaces\Tupleable;

class AsJson extends Filter
{
    public function __invoke($using)
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
        $tuple = AsTuple::fromBoolean($using);
        return json_encode($tuple);
    }

    // TODO: PHP 8 - int|float
    static public function fromNumber($using): string
    {
        $tuple = AsTuple::fromNumber($using);
        return json_encode($tuple);
    }

    static public function fromString(string $using): string
    {
        if (IsJson::apply()->unfoldUsing($using)) {
            return $using;
        }

        $tuple = AsTuple::fromString($using);
        return json_encode($tuple);
    }

    static public function fromList(array $using): string
    {
        $tuple = AsTuple::fromList($using);
        return json_encode($tuple);
    }

    static public function fromTuple($using): string
    {
        $tuple = AsTuple::fromTuple($using);
        return json_encode($tuple);
    }

    static public function fromJson(string $using): string
    {
        if (IsJson::apply()->unfoldUsing($using)) {
            return $using;
        }
        return static::fromString($using);
    }

    static public function fromObject(object $using): string
    {
        if (is_a($using, Tupleable::class)) {
            return $using->efToJson();
        }
        return static::fromTuple($using);
    }
}
