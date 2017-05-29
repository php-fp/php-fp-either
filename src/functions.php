<?php
declare(strict_types=1);

namespace PhpFp\Either;

/**
 * Capture an exception-throwing function in an Either.
 * @param callable $f The exception-throwing function.
 * @return Either Right (with success), or Left (with exception).
 */
function try_catch(callable $f): Either
{
    try {
        return Right::of($f());
    } catch (\Exception $e) {
        return Left::of($e);
    }
}
