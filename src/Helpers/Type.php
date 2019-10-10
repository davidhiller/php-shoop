<?php

namespace Eightfold\Shoop\Helpers;

use Eightfold\Shoop\Interfaces\Shooped;

use Eightfold\Shoop\{
    Shoop,
    ESBool,
    ESInt,
    ESString,
    ESArray,
    ESObject
};

class Type
{
    static public function map(): array
    {
        return [
            "bool"    => ESBool::class,
            "boolean" => ESBool::class,
            "int"     => ESInt::class,
            "integer" => ESInt::class,
            "string"  => ESString::class,
            "array"   => ESArray::class,
            "object"  => ESObject::class
        ];
    }

    static public function phpToShoop(string $phpType): string
    {
        $map = static::map();
        if (array_key_exists($phpType, $map)) {
            return $map[$phpType];
        }
        return "";
    }

    static public function shoopToPhp(string $shoopType): string
    {
        $map = static::map();
        if (in_array($shoopType, $map)) {
            $value = array_search($shoopType, $map);
            return $value;
        }
        return "";
    }

    static public function for($potential): string
    {
        if (static::isShooped($potential)) {
            return get_class($potential);
        }

        $type = gettype($potential);
        if ($type === "integer") {
            $type = "int";

        } elseif ($type === "boolean") {
            $type = "bool";

        }
        return $type;
    }

    static public function shoopFor($potential)
    {
        if (static::isShooped($potential)) {
            return get_class($potential);
        }

        return self::phpToShoop(self::for($potential));
    }

    static public function isShooped($potential): bool
    {
        return $potential instanceOf Shooped;
    }

    static public function isNotShooped($potential)
    {
        return ! static::isShooped($potential);
    }

    static public function isPhp($potential): bool
    {
        if (static::isShooped($potential)) {
            return false;
        }

        return array_key_exists(static::for($potential), static::map());
    }

    static public function isNotPhp($potential): bool
    {
        return ! static::isPhp($potential);
    }

    static public function isArray($potential): bool
    {
        return is_array($potential)
            || (self::isShooped($potential) && is_a($potential, ESArray::class));
    }

    static public function isNotArray($potential): bool
    {
        return ! static::isArray($potential);
    }

    static public function isDictionary($potential): bool
    {
        if (static::isArray($potential)) {
            return array_keys($potential) !== range(0, count($potential) - 1);
        }
    }

    static public function sanitizeType($toSanitize, string $shoopType = "")
    {
        if (Type::isShooped($toSanitize)) {
            return $toSanitize;
        }

        if (Type::isPhp($toSanitize) && strlen($shoopType) === 0) {
            $desiredPhpType = Type::for($toSanitize);
            $shoopType = Type::phpToShoop($desiredPhpType);

        }

        if (isset($desiredPhpType)) {
            self::isDesiredTypeOrTriggerError($desiredPhpType, $toSanitize);

        } elseif (strlen($shoopType) === 0) {
            $shoopType = Type::phpToShoop(Type::for($toSanitize));

        }

        return $shoopType::fold($toSanitize);
    }

    static private function isDesiredTypeOrTriggerError($desiredPhpType, $variable)
    {
        $sanitizeType = Type::for($variable);
        if ($sanitizeType !== $desiredPhpType) {
            list($_, $caller) = debug_backtrace(false);
            self::invalidTypeError($desiredPhpType, $sanitizeType, $caller);
        }
    }

    static private function invalidTypeError($desiredPhpType, $sanitizeType, $caller)
    {
        $className = $caller['class'];
        $functionName = $caller['function'];
        $myClass = static::class;
        trigger_error(
            "Argument 1 passed to {$functionName} in {$className} must be of type {$desiredPhpType} or an instance of {$myClass} received {$sanitizeType} instead",
            E_USER_ERROR
        );
    }
}
