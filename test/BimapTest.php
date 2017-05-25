<?php

namespace PhpFp\Either\Test;

use PhpFp\Either\Either;
use PhpFp\Either\{Left, Right};

class BimapTest extends \PHPUnit_Framework_TestCase
{
    public function testBimapParameterCount()
    {
        $count = (new \ReflectionMethod('PhpFp\Either\Left::bimap'))
            ->getNumberOfParameters();

        $this->assertEquals($count,
            2,
            'Left::bimap takes two parameters.'
        );

        $count = (new \ReflectionMethod('PhpFp\Either\Right::bimap'))
            ->getNumberOfParameters();

        $this->assertEquals($count,
            2,
            'Right::bimap takes two parameters.'
        );
    }

    public function testBimap()
    {
        $addOne = function ($x)
        {
            return $x + 1;
        };

        $takeOne = function ($x)
        {
            return $x - 1;
        };

        $id = function ($x)
        {
            return $x;
        };

        $a = Right::of(2);
        $b = Left::of(2);

        $this->assertEquals(
            $a->bimap($addOne, $takeOne)
                ->either($id, $id),
            1,
            'Bimaps a Right.'
        );

        $this->assertEquals(
            $b->bimap($addOne, $takeOne)
                ->either($id, $id),
            3,
            'Bimaps a Left.'
        );
    }
}
