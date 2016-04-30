<?php

namespace PhpFp\Either\Test;

use PhpFp\Either\Either;
use PhpFp\Either\Constructor\{Left, Right};

class ApTest extends \PHPUnit_Framework_TestCase
{
    public function testApParameterCount()
    {
        $count = (new \ReflectionMethod('PhpFp\Either\Constructor\Left::ap'))
            ->getNumberOfParameters();

        $this->assertEquals($count,
            1,
            'Left::ap takes one parameter.'
        );

        $count = (new \ReflectionMethod('PhpFp\Either\Constructor\Right::ap'))
            ->getNumberOfParameters();

        $this->assertEquals($count,
            1,
            'Right::ap takes one parameter.'
        );
    }

    public function testAp()
    {
        $addTwo = Either::of(
            function ($x)
            {
                return $x + 2;
            }
        );

        $id = function ($x)
        {
            return $x;
        };

        $a = Either::of(5);
        $b = new Left(4);

        $this->assertEquals(
            $addTwo
                ->ap($a)
                ->either($id, $id),
            7,
            'Applies to a Right.'
        );

        $this->assertEquals(
            $addTwo->ap($b)->either($id, $id),
            4,
            'Applies to a Left.'
        );
    }
}
