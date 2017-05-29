<?php

namespace PhpFp\Either\Test;

use PhpFp\Either\{Left, Right};

use function PhpFp\Either\try_catch;

class TryCatchTest extends \PHPUnit_Framework_TestCase
{
    public function testTryCatchParameterCount()
    {
        $count = (new \ReflectionFunction('PhpFp\Either\try_catch'))
            ->getNumberOfParameters();

        $this->assertEquals($count,
            1,
            'try_catch takes one parameter.'
        );
    }

    public function testTryCatch()
    {
        $f = function ($bad)
        {
            return function () use ($bad)
            {
                if ($bad) throw new \Exception;

                return 'No exception';
            };
        };

        $id = function ($x) { return $x; };

        $this->assertEquals(
            try_catch($f(false))->either($id, $id),
            'No exception',
            'try_catch produces a Right.'
        );

        $this->assertInstanceOf(
            'Exception',
            try_catch($f(true))->either($id, $id),
            'try_catch produces a Left.'
        );
    }
}
