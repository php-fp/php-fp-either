<?php

namespace PhpFp\Either\Constructor;

use PhpFp\Either\Either;

/**
 * An OO-looking implementation of the Right constructor.
 */
final class Right extends Either
{
    /**
     * Apply a wrapped paramater to this wrapped function.
     * @param Either $that The parameter to apply.
     * @return Either The wrapped result.
     */
    public function ap(Either $that) : Either
    {
        return $this->chain(
            function ($f) use ($that)
            {
                return $that->map($f);
            }
        );
    }

    /**
     * Map over both sides of the Either.
     * @param callable $f The Left transformer.
     * @param callable $g The Right transformer.
     * @return Either Both sides transformed.
     */
    public function bimap(callable $_, callable $g) : Either
    {
        return Either::right($g($this->value));
    }

    /**
     * Monadic flat map for Right instances. (>>=).
     * @param callable $f a -> Either e b
     * @return Either Either e b
     */
    public function chain(callable $f) : Either
    {
        return $f($this->value);
    }

    /**
     * Transform the inner value.
     * @param callable $f The transformer for the inner value.
     * @return Either The wrapped, transformed value.
     */
    public function map(callable $f) : Either
    {
        return Either::right($f($this->value));
    }

    /**
     * Transform the Right value with the right function.
     * @param callable $f The transformer for a Left value.
     * @param callable $g The transformer for a Right value.
     * @return mixed Whatever the returned type is.
     */
    public function either(callable $_, callable $g)
    {
        return $g($this->value);
    }
}
