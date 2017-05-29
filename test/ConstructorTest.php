<?php

namespace PhpFp\Either\Test;

use PhpFp\Either\Either;
use PhpFp\Either\{Left, Right};

class ConstructorTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorParameterCount()
    {
        $count = (new \ReflectionClass('PhpFp\Either\Left'))
            ->getConstructor()->getNumberOfParameters();

        $this->assertEquals(
            $count,
            1,
            'Left constructor takes one parameter.'
        );

        $count = (new \ReflectionClass('PhpFp\Either\Right'))
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
            (Right::of(2))->either($id, $id),
            2,
            'Constructs a Right.'
        );

        $this->assertEquals(
            (Left::of(2))->either($id, $id),
            2,
            'Constructs a Left.'
        );
    }

    public function testStaticConstructor()
    {
        $this->assertInstanceOf(
            Right::class,
            Right::of('a'),
            'Statically constructs a Right.'
        );

        $this->assertInstanceOf(
            Left::class,
            Left::of('b'),
            'Statically constructs a Left.'
        );
    }
}
