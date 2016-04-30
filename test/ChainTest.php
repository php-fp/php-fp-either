<?php

namespace PhpFp\Either\Test;

use PhpFp\Either\Either;
use PhpFp\Either\Constructor\{Left, Right};

class ChainTest extends \PHPUnit_Framework_TestCase
{
    public function testChainParameterCount()
    {
        $count = (new \ReflectionMethod('PhpFp\Either\Constructor\Left::chain'))
            ->getNumberOfParameters();

        $this->assertEquals($count,
            1,
            'Left::chain takes one parameter.'
        );

        $count = (new \ReflectionMethod('PhpFp\Either\Constructor\Right::chain'))
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
            return Either::of($x + 2);
        };

        $id = function ($x)
        {
            return $x;
        };

        $a = Either::of(5);
        $b = new Left(4);

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
