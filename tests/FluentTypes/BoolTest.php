<?php

namespace Eightfold\Shoop\Tests;

use PHPUnit\Framework\TestCase;
use Eightfold\Shoop\Tests\AssertEqualsFluent;

class BoolTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        $this->assertTrue(true);
    }
}
