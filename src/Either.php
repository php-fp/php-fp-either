<?php

namespace PhpFp\Either;

use PhpFp\Either\Constructor\{Left, Right};

/**
 * An OO-looking implementation of Either in PHP.
 */
abstract class Either
{
    /**
     * Construct a new Left instance with a value.
     * @param mixed $x The value to be wrapped.
     * @return A new Left-constructed type.
     */
    final public static function left($x) : Left
    {
        return new Left($x);
    }

    /**
     * Construct a new Right instance with a value.
     * @param mixed $x The value to be wrapped.
     * @return A new Right-constructed type.
     */
    final public static function right($x) : Right
    {
        return new Right($x);
    }

    /**
     * Applicative constructor for Either.
     * @param mixed $x The value to be wrapped.
     * @return A new Right-constructed type.
     */
    final public static function of($x) : Either
    {
        return self::right($x);
    }

    /**
     * Capture an exception-throwing function in an Either.
     * @param callable $f The exception-throwing function.
     * @return Either Right (with success), or Left (with exception).
     */
    final public static function tryCatch(callable $f) : Either
    {
        try {
            return self::of($f());
        } catch (\Exception $e) {
            return self::left($e);
        }
    }

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

    /**
     * The inner value of the instance.
     * @var mixed
     */
    protected $value = null;

    /**
     * Standard constructor for an Either instance.
     * @param mixed $value The value to wrap.
     */
    final private function __construct($value)
    {
        $this->value = $value;
    }
}
