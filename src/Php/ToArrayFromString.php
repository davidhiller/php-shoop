<?php
declare(strict_types=1);

namespace Eightfold\Shoop\Php;

use Eightfold\Foldable\Bend;

use Eightfold\Shoop\Shoop;

class ToArrayFromString extends Bend
{
    private $splitter = "";
    private $includeEmpties = true;
    private $limit = PHP_INT_MAX;

    public function __construct(
        string $splitter = "",
        bool $includeEmpties = true,
        int $limit = PHP_INT_MAX
    )
    {
        $this->splitter = $splitter;
        $this->includeEmpties = $includeEmpties;
        $this->limit = $limit;
    }

    public function __invoke(string $payload): array
    {
        if (empty($this->splitter)) {
            return preg_split('//u', $payload, -1, PREG_SPLIT_NO_EMPTY);
        }
        $array = explode($this->splitter, $payload, $this->limit);
        return ($this->includeEmpties)
            ? $array
            : Shoop::pipeline($array, StripArray::bend(), ValuesFromArray::bend())->unfold();
    }
}
