<?php

namespace Eightfold\Shoop\Traits;

use Eightfold\Shoop\Helpers\Type;

use Eightfold\Shoop\{
    Shoop,
    ESArray,
    ESBool,
    ESInt,
    ESString,
    ESObject,
    ESJson,
    ESDictionary
};

trait SortImp
{
    public function sort($asc = true, $caseSensitive = true)
    {
        if (Type::is($this, ESArray::class)) {
            $array = $this->value;
            $array = $this->indexedArrayToSortedIndexedArray($array, $asc, $caseSensitive);
            return Shoop::array($array);

        } elseif (Type::is($this, ESDictionary::class)) {
            $dictionary = $this->value;
            $dictionary = $this->associativeArrayToSortedAssociativeArray($dictionary, $asc, $caseSensitive);
            return Shoop::dictionary($dictionary);

        } elseif (Type::is($this, ESJson::class)) {
            $json = $this->value;
            $object = json_decode($json);
            $object = $this->objectToSortedObject($object, $asc, $caseSensitive);
            $json = json_encode($object);
            return Shoop::json($json);

        } elseif (Type::is($this, ESObject::class)) {
            $object = $this->value;
            $object = $this->objectToSortedObject($object, $asc, $caseSensitive);
            return Shoop::object($object);

        } elseif (Type::is($this, ESString::class)) {
            $string = $this->value;
            $array = $this->stringToIndexedArray($string);
            $array = $this->indexedArrayToSortedIndexedArray($array, $asc, $caseSensitive);
            $string = implode("", $array);
            return Shoop::string($string);

        }
    }

    private function indexedArrayToSortedIndexedArray(array $array, bool $asc, bool $case): array
    {
        if ($asc) {
            if ($caseSensitive) {
                sort($array, SORT_NATURAL);

            } else {
                sort($array, SORT_NATURAL | SORT_FLAG_CASE);

            }

        } else {
            if ($caseSensitive) {
                rsort($array, SORT_NATURAL);

            } else {
                rsort($array, SORT_NATURAL | SORT_FLAG_CASE);

            }
        }
        return $array;
    }
    private function associativeArrayToSortedAssociativeArray(array $dictionary, bool $asc, bool $case): array
    {
        if ($asc) {
            if ($caseSensitive) {
                asort($dictionary, SORT_NATURAL);

            } else {
                asort($dictionary, SORT_NATURAL | SORT_FLAG_CASE);

            }

        } else {
            if ($caseSensitive) {
                arsort($dictionary, SORT_NATURAL);

            } else {
                arsort($dictionary, SORT_NATURAL | SORT_FLAG_CASE);

            }
        }
        return $dictionary;
    }

    private function objectToSortedObject(object $object, bool $asc, bool $caseSensitive): object
    {
        $dictionary = (array) $object;
        $dictionary = $this->associativeArrayToSortedAssociativeArray($dictionary, $asc, $caseSensitive);
        $object = (object) $dictionary;
        return $object;
    }
    // /**
    //  * Doesn't account for SORT_STRING or SORT_LOCALE_STRING
    //  *
    //  * Favors natural ordering instead of regular, use "php" to use regular.
    //  *
    //  * ESArray will have indeces reset (first will always be 0) and
    //  * ESDictionary will always maintain key-value association.
    //  *
    //  * []       = SORT_NATURAL | SORT_FLAG_CASE (values)
    //  * ["keys"] = SORT_NATURAL | SORT_FLAG_CASE (keys)
    //  *
    //  * ["case"]         = SORT_NATURAL (values)
    //  * ["keys", "case"] = SORT_NATURAL (keys)
    //  *
    //  * ["num"]         = SORT_NUMERIC (values)
    //  * ["keys", "num"] = SORT_NUMERIC (keys)
    //  *
    //  * ["php"]         = SORT_REGULAR (values)
    //  * ["keys", "php"] = SORT_REGULAR (keys)
    //  *
    //  * desc = true,    case = (true), keys = (true), method = (nat)|num|php
    //  * desc = true,    case = true,   keys = false
    //  * desc = true,    case = false,  keys = false
    //  * desc = true,    case = false,  keys = true
    //  *
    //  * desc = (false), case = false,  keys = false
    //  * desc = false,   case = false,  keys = true
    //  * desc = false,   case = true,   keys = true
    //  * desc = false,   case = true,   keys = false
    //  *
    //  * @param array $flags Order does not matter
    //  *                 desc = will sort high to low (otherwise will be high to low)
    //  *                 case = will use a case sensitive compare. (if "num" is present, "num" will be used.)
    //  *                 keys = will sort keys
    //  *
    //  *                 Only one of the following may be present
    //  *                 nat  = will use natural sorting. (Can be used with case.)
    //  *                 num  = will use numeric sorting.
    //  *                 php  = will use regular sorting (default for PHP).
    //  */
    // public function sort(?string ...$flags)
    // {
    //     if ($flags === null) {
    //         $flags = [];
    //     }

    //     $desc = in_array("desc", $flags);

    //     // TODO: Is it really viable to has case sensitive sorting with keys?
    //     //       Wouldn't keys first overwrite each other despite casing?
    //     $keys = in_array("keys", $flags);
    //     $case = in_array("case", $flags);

    //     $nat  = in_array("nat", $flags);
    //     $num  = in_array("num", $flags);
    //     $php  = in_array("php", $flags);

    //     if ($case && $num) {
    //         $case = false;

    //     }

    //     // only one can be true - favors natural
    //     if ($nat && $num && $php) {
    //         $num = false;
    //         $php = false;

    //     } elseif (! $nat && $num && $php) {
    //         $php = false;

    //     } elseif ($nat && ! $num && $php) {
    //         $php = false;

    //     } elseif ($nat && $num && ! $php) {
    //         $num = false;

    //     }

    //     // TODO: Believe this can be simplified or recuced, not sure tests to verify?
    //     $subject = $this->array();
    //     if (Type::isArray($this)) {
    //         $subject = $this->unfold();
    //         if ($php) {
    //             sort($subject, SORT_REGULAR);

    //         } elseif ($num) {
    //             sort($subject, SORT_NUMERIC);

    //         } elseif ($case) {
    //             sort($subject, SORT_NATURAL);

    //         } else {
    //             sort($subject, SORT_NATURAL | SORT_FLAG_CASE);

    //         }
    //         $subject = Shoop::array(array_values($subject));

    //     } elseif (Type::isDictionary($this)) {
    //         $subject = $this->unfold();
    //         if ($keys) {
    //             if ($php) {
    //                 ksort($subject);

    //             } elseif ($num) {
    //                 ksort($subject, SORT_NUMERIC);

    //             } else {
    //                 ksort($subject, SORT_NATURAL | SORT_FLAG_CASE);

    //             }

    //         } else {
    //             if ($case) {
    //                 natsort($subject);

    //             } else {
    //                 natcasesort($subject);

    //             }
    //         }
    //         $subject = Shoop::dictionary($subject);
    //     }
    //     return $subject;
    // }
}
