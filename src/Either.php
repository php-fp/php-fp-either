<?php

namespace PhpFp\Either;

/**
 * An OO-looking implementation of Either in PHP.
 */
abstract class Either
{
    /**
     * The inner value of the instance.
     * @var mixed
     */
    protected $value = null;

    /**
     * Apply a wrapped parameter to this wrapped function.
     * @param Either $that The wrapped parameter.
     * @return Either The wrapped result.
     */
    abstract public function ap(Either $that) : Either;

    /**
     * Map over both sides of the Either.
     * @param callable $f The Left transformer.
     * @param callable $g The Right transformer.
     * @return Either Both sides transformed.
     */
    abstract public function bimap(callable $f, callable $g) : Either;

    /**
     * PHP implementation of Haskell Either's bind (>>=).
     * @param callable $f a -> Either e b
     * @return Either Either e b
     */
    abstract public function chain(callable $f) : Either;

    /**
     * Standard functor mapping, derived from chain.
     * @param callable $f The transformer for the inner value.
     * @return Either The wrapped, transformed value.
     */
    abstract public function map(callable $f) : Either;

    /**
     * Read the value within the monad, left or right.
     * @param callable $f Transformation for Left.
     * @param callable $g Transformation for Right.
     * @return mixed The same type for each branch.
     */
    abstract public function either(callable $f, callable $g);
}
