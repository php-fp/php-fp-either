<?php

namespace PhpFp\Either\Test;

use PhpFp\Either\Either;
use PhpFp\Either\Constructor\{Left, Right};

class TryCatchTest extends \PHPUnit_Framework_TestCase
{
    public function testTryCatchParameterCount()
    {
        $count = (new \ReflectionMethod('PhpFp\Either\Either::tryCatch'))
            ->getNumberOfParameters();

        $this->assertEquals($count,
            1,
            'tryCatch takes one parameter.'
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
            Either::tryCatch($f(false))->either($id, $id),
            'No exception',
            'TryCatches a Right.'
        );

        $this->assertInstanceOf(
            'Exception',
            Either::tryCatch($f(true))->either($id, $id),
            'TryCatches a Left.'
        );
    }
}
