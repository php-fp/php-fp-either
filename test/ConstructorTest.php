<?php

namespace PhpFp\Either\Test;

use PhpFp\Either\Either;
use PhpFp\Either\Constructor\{Left, Right};

class ConstructorTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorParameterCount()
    {
        $count = (new \ReflectionClass('PhpFp\Either\Constructor\Left'))
            ->getConstructor()->getNumberOfParameters();

        $this->assertEquals(
            $count,
            1,
            'Left constructor takes one parameter.'
        );

        $count = (new \ReflectionClass('PhpFp\Either\Constructor\Right'))
            ->getConstructor()->getNumberOfParameters();

        $this->assertEquals(
            $count,
            1,
            'Right constructor takes one parameter.'
        );
    }

    public function testConstructor()
    {
        $id = function ($x)
        {
            return $x;
        };

        $this->assertEquals(
            (new Right(2))->either($id, $id),
            2,
            'Constructs a Right.'
        );

        $this->assertEquals(
            (new Left(2))->either($id, $id),
            2,
            'Constructs a Left.'
        );
    }
}
