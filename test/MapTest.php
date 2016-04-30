<?php

namespace PhpFp\Either\Test;

use PhpFp\Either\Either;
use PhpFp\Either\Constructor\{Left, Right};

class MapTest extends \PHPUnit_Framework_TestCase
{
    public function testMapParameterCount()
    {
        $count = (new \ReflectionMethod('PhpFp\Either\Constructor\Left::map'))
            ->getNumberOfParameters();

        $this->assertEquals($count,
            1,
            'Left::map takes one parameter.'
        );

        $count = (new \ReflectionMethod('PhpFp\Either\Constructor\Right::map'))
            ->getNumberOfParameters();

        $this->assertEquals($count,
            1,
            'Right::map takes one parameter.'
        );
    }

    public function testMap()
    {
        $addTwo = function ($x)
        {
            return $x + 2;
        };

        $id = function ($x)
        {
            return $x;
        };

        $a = new Right(5);
        $b = new Left(4);

        $this->assertEquals(
            $a->map($addTwo)->either($id, $id),
            7,
            'Maps a Right.'
        );

        $this->assertEquals(
            $b->map($addTwo)->either($id, $id),
            4,
            'Maps a Left.'
        );
    }
}
