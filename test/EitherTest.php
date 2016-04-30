<?php

namespace PhpFp\Either\Test;

use PhpFp\Either\Either;
use PhpFp\Either\Constructor\{Left, Right};

class EitherTest extends \PHPUnit_Framework_TestCase
{
    public function testEitherParameterCount()
    {
        $count = (new \ReflectionMethod('PhpFp\Either\Constructor\Left::either'))
            ->getNumberOfParameters();

        $this->assertEquals($count,
            2,
            'Left::either takes two parameters.'
        );

        $count = (new \ReflectionMethod('PhpFp\Either\Constructor\Right::either'))
            ->getNumberOfParameters();

        $this->assertEquals($count,
            2,
            'Right::either takes two parameters.'
        );
    }

    public function testEither()
    {
        $addOne = function ($x)
        {
            return $x + 1;
        };

        $takeOne = function ($x)
        {
            return $x - 1;
        };

        $a = new Right(2);
        $b = new Left(2);

        $this->assertEquals(
            $a->either($addOne, $takeOne),
            1,
            'Eithers a Right.'
        );

        $this->assertEquals(
            $b->either($addOne, $takeOne),
            3,
            'Eithers a Left.'
        );
    }
}
