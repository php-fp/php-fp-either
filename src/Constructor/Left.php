<?php

namespace PhpFp\Either\Constructor;

use PhpFp\Either\Either;

/**
 * An OO-looking implementation of the Left constructor.
 */
final class Left extends Either
{
    /**
     * Do nothing; return the same value.
     * @param Either $that The parameter to apply.
     * @return Either The wrapped result.
     */
    public function ap(Either $that) : Either
    {
        return $that;
    }

    /**
     * Map over both sides of the Either.
     * @param callable $f The Left transformer.
     * @param callable $g The Right transformer.
     * @return Either Both sides transformed.
     */
    public function bimap(callable $f, callable $_) : Either
    {
        return Either::left($f($this->value));
    }

    /**
     * Do nothing; return the same value.
     * @param callable $f a -> Either e b
     * @return Either Either e b
     */
    public function chain(callable $f) : Either
    {
        return $this;
    }

    /**
     * Do nothing; return the same value.
     * @param callable $f The transformer for the inner value.
     * @return Either The wrapped, transformed value.
     */
    public function map(callable $f) : Either
    {
        return $this;
    }

    /**
     * Transform the Left value with the left function.
     * @param callable $f The transformer for a Left value.
     * @param callable $g The transformer for a Right value.
     * @return mixed Whatever the returned type is.
     */
    public function either(callable $f, callable $_)
    {
        return $f($this->value);
    }
}
