<?php

namespace PhpFp\Either\Test;

use PhpFp\Either\Either;
use PhpFp\Either\{Left, Right};

class ChainTest extends \PHPUnit_Framework_TestCase
{
    public function testChainParameterCount()
    {
        $count = (new \ReflectionMethod('PhpFp\Either\Left::chain'))
            ->getNumberOfParameters();

        $this->assertEquals($count,
            1,
            'Left::chain takes one parameter.'
        );

        $count = (new \ReflectionMethod('PhpFp\Either\Right::chain'))
            ->getNumberOfParameters();

        $this->assertEquals($count,
            1,
            'Right::chain takes one parameter.'
        );
    }

    public function testChain()
    {
        $addTwo = function ($x)
        {
            return Right::of($x + 2);
        };

        $id = function ($x)
        {
            return $x;
        };

        $a = Right::of(5);
        $b = Left::of(4);

        $this->assertEquals(
            $a->chain($addTwo)->either($id, $id),
            7,
            'Chains a Right.'
        );

        $this->assertEquals(
            $b->chain($addTwo)->either($id, $id),
            4,
            'Chains a Left.'
        );
    }
}
