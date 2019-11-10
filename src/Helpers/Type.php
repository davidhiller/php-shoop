<?php

namespace Eightfold\Shoop\Helpers;

use Eightfold\Shoop\Interfaces\Shooped;

use Eightfold\Shoop\{
    Shoop,
    ESBool,
    ESInt,
    ESString,
    ESArray,
    ESDictionary,
    ESObject,
    ESJson
};

class Type
{
    static public function sanitizeType($toSanitize, string $shoopType = "")
    {
        if (Type::isShooped($toSanitize)) {
            return $toSanitize;
        }

        $shoopType = (strlen($shoopType) === 0) ? Type::shoopFor($toSanitize) : $shoopType;
        if (strlen($shoopType) > 0) {
            return $shoopType::fold($toSanitize);
        }
        return $toSanitize;
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

// - Check types
    static public function is($potential, string ...$types): bool
    {
        $potentialType = self::for($potential);
        foreach ($types as $type) {
            if ($potentialType === $type) {
                return true;
            }
        }
        return false;
    }

    static public function for($potential): string
    {
        if (static::isShooped($potential)) {
            return get_class($potential);
        }

        $type = gettype($potential);

        if ($type === "object" && ! is_a($potential, \stdClass::class)) {
            return get_class($potential);
        }

        if ($type === "integer") {
            $type = "int";

        } elseif ($type === "boolean") {
            $type = "bool";

        } elseif (Type::isJson($potential)) {
            $type = "json";

        } elseif ($type === "array" && Type::isDictionary($potential)) {
            $type = "dictionary";

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
        if (Type::isShooped($potential)) {
            return false;
        }

        $custom = (is_string($potential)) ? explode("\\", $potential) : $potential;
        if (is_array($custom) && count($custom) > 1) {
            return false;
        }

        if (is_object($potential) && ! is_a($potential, \stdClass::class)) {
            return false;
        }

        $phpTypes = array_keys(Type::map());
        array_pop($phpTypes);
        if (in_array(gettype($potential), $phpTypes)) {
            return true;
        }

        return false;
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
        if (Type::isShooped($potential)) {
            $potential = $potential->unfold();
        }

        if (Type::isArray($potential)) {
            // https://stackoverflow.com/questions/173400
            return array_keys($potential) !== range(0, count($potential) - 1);
        }

        return false;
    }

    static public function isJson($potential): bool
    {
        $isString = is_string($potential);
        if ($isString) {
            $potential = trim($potential);
            $isDecodable = is_array(json_decode($potential, true));
            $noJsonError = (json_last_error() == JSON_ERROR_NONE);

            $startsWithBrace = ESString::fold($potential)->startsWith("{")->unfold();
            $endsWithBrace = ESString::fold($potential)->endsWith("}")->unfold();
        }
        return ($isString && $isDecodable && $noJsonError && $startsWithBrace && $endsWithBrace);
    }

    static public function isNotJson($potential): bool
    {
        return ! self::isJson($potential);
    }

    static public function isObject($potential): bool
    {
        return (is_object($potential) && self::isPhp($potential))
            || (self::isShooped($potential) && is_a($potential, ESObject::class));
    }

    static public function isPath($potential): bool
    {
        return Shoop::string($potential)->hasUnfolded("/") && self::isNotUri($potential);
    }

    static public function isUri($potential): bool
    {
        $potential = Shoop::string($potential);
        return $potential->has("/")->and($potential->startsWith("http"))->unfold();
    }

    static public function isNotUri($potential)
    {
        return ! self::isUri($potential);
    }

    static public function isEmpty(Shooped $check): bool
    {
        $check = Type::sanitizeType($check, get_class($check))->unfold();
        return empty($check);
    }

// - Type metadata
    static public function map(): array
    {
        return [
            "bool"       => ESBool::class,
            "boolean"    => ESBool::class,
            "int"        => ESInt::class,
            "integer"    => ESInt::class,
            "string"     => ESString::class,
            "array"      => ESArray::class,
            "dictionary" => ESDictionary::class,
            "object"     => ESObject::class,
            "json"       => ESJson::class
        ];
    }

    static public function phpToShoop(string $phpType): string
    {
        $map = static::map();
        return (array_key_exists($phpType, $map))
            ? $map[$phpType]
            : "";
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
}
