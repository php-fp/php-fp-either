# The Either Monad for PHP. [![Build Status](https://travis-ci.org/php-fp/php-fp-either.svg?branch=master)](https://travis-ci.org/php-fp/php-fp-either)

## Intro

When you throw an exception in PHP, you effectively perform a `GOTO`: your position in the program's execution jumps to the appropriate exception handler, and execution continues. This is fine, but it obviously means that your original function has a side-effect: calling it will fundamentally alter the program flow. What we need is a _functional_ way of accomplishing the same thing.

Enter, the Either monad. `Either` has two constructors, `Left` and `Right`. These work very similarly to `Maybe`'s `Just` and `Nothing`: `map`, `ap`, and `chain` work as you'd expect on the `Right` instance, but are effectively no-ops on the `Left`. However, the difference is that `Left`, unlike `Nothing`, holds a value.

This means that, typically, your left branch will hold the 'exception', and the right will hold the value. If an exception happens, all future computations are ignored, and the exception can be handled in a pure way. For example:

```php
<?php

use PhpFp\Either\Constructor\{Left, Right};

$login = function ($username, $password)
{
    if ($username != 'foo') {
        return new Left(
            'Invalid username'
        );
    }

    if ($password != 'bar') {
        return new Left(
            'Incorrect password'
        );
    }

    return new Right(['hello' => 'world']);
}

$prop = function ($k)
{
    return function ($xs) use ($k)
    {
        return isset ($xs[$k])
            ? new Right($xs[$k])
            : new Left('No such key.');
    }
}

$id = function ($x)
{
    return $x;
};

// Some examples...
$badUsername = $login('fur', 'bar')->chain($prop('id'));
$badPassword = $login('foo', 'bear')->chain($prop('id'));
$badKey = $login('foo', 'bar')->chain($prop('brian'));
$good = $login('foo', 'bar')->chain($prop('id'));

assert($badUsername->either($id, $id) === 'Invalid username');
assert($badPassword->either($id, $id) === 'Incorrect password');
assert($badKey->either($id, $id) === 'No such key.');
assert($good->either($id, $id) === 'world');
```

As the above shows, a failure is carried through the computation and all further operations (with the only (for now) exception of `bimap` below), and _must_ be handled by `either`, the function for retrieving the inner value.

Of course, exceptions are the usual analogy, but `Either` is a more general type, and is helpful in most computations with two potential values. What if a user can input via a file or `stdin`? We could use `Either String File`, map over the instance with a `File -> String` function, then extract the value once we know they're both acceptable.

## API

In the following type signatures, constructors and static functions are written as one would see in pure languages such as Haskell. The others contain a pipe, where the type before the pipe represents the type of the current Either instance, and the type after the pipe represents the function.

### `of :: a -> Either e a`

This is the applicative constructor for the Either monad. It returns the given value wrapped in a `Right` instance:

```php
<?php

use PhpFp\Either\Either;

$id = function ($x) { return $x; };

assert(Either::of('test')->either($id, $id) == 'test');
```

### `tryCatch :: (-> a) -> Either e a`

Sometimes, you will have a piece of exception-throwing code that you wish to wrap in an `Either`, and this function can help. If an exception occurs, it will be wrapped and returned in a `Left`. Otherwise, the returned value will be wrapped in a `Right`:

```php
<?php

use PhpFp\Either\Either;

$id = function ($x) { return $x; };

$f = function () { throw new \Exception; };
$g = function () { return 'hello'; };

assert(Either::tryCatch($f)->either($id, $id) instanceof \Exception);
assert(Either::tryCatch($g)->either($id, $id) === 'hello');
```

### `__construct :: a -> Either e a`

Standard constructor for the `Either` instances. `PhpFp\Either\Either` has an abstract constructor, so you will need to call either `PhpFp\Either\Constructor\Left::__construct` or the `Right` equivalent.

### `ap :: Either e (a -> b) | Either e a -> Either e b`

Apply an Either-wrapped argument to an Either-wrapped function, where a `Left` function will behave as identity.

```php
<?php

use PhpFp\Either\Constructor\{Left, Right};

$id = function ($x) { return $x; };

$addTwo = Either::of(
    function ($x)
    {
        return $x + 2;
    }
);

$a = new Right(5);
$b = new Left(4);

assert($addTwo->ap($a)->either($id , $id) === 7);
assert($addTwo->ap($b)->either($id, $id) === 4);
```

### `bimap :: Either e a | (e -> f) -> (a -> b) -> Either f b`

Sometimes, it can be useful to define computations to be performed on the `Left` values, and this is the way to do so. For this function, you supply left and right transformations, and the appropriate one will be used:

```php
<?php

use PhpFp\Either\Constructor\{Left, Right};

$addOne = function ($x) { return $x + 1; };
$subOne = function ($x) { return $x - 1; };
$id = function ($x) { return $x; };

assert ((new Right(2))->bimap($addOne, $subOne)->either($id, $id) === 1);
assert ((new Left(2))->bimap($addOne, $subOne)->either($id, $id) === 3);
```

### `chain :: Either e a | (a -> Either f b) -> Either f b`

The standard monadic binding function (Haskell's `>>=`). This is for mapping with a function that returns an Either value: instead of using `map` and getting `Either e (Either e a)`, you get `Either e a` and the two levels are "flattened". The introduction has a good example, but here's a smaller one:

```php
<?php

use PhpFp\Either\Constructor\{Left, Right};

$f = function ($x)
{
    return Either::of($x * 2);
}

$id = function ($x) { return $x; };

assert((new Right(8))->chain($f)->either($id, $id) === 16);
assert((new Left(8))->chain($f)->either($id, $id) === 8);
```

### `map :: Either e a | (a -> b) -> Either e b`

This is the standard functor map, which transforms the inner value. As with the other `Either` operations, remember that this has no impact on a `Left` value, which can only be transformed with `bimap`:

```php
<?php

use PhpFp\Either\Constructor\{Left, Right};

$f = function ($x) { return $x - 5; };
$id = function ($x) { return $x; };

assert((new Right(8))->map($f)->either($id, $id) === 3);
assert((new Left(8))->map($f)->either($id, $id) === 8);
```

### `either :: Either e a | (e -> b) -> (a -> b) -> b`

This is the function that should be used to get the value _out_ of the `Either` monad. Strictly, if you're being well-behaved and watching your types, the two supplied functions, while potentially accepting differently-typed inputs for `Left` and `Right`, should return values of the **same** type:

```php
<?php

use PhpFp\Either\Constructor\{Left, Right};

$left = function ($x) { return (int) $x; };
$right = function ($x) { $x; };

assert((new Left('7'))->either($left, $right) === 7);
assert((new Right(2))->either($left, $right) === 2);
```

## Contributing

Similarly to the others, I'm aware of at least a couple of typeclasses that could be added to this implementation, so feel free to submit issues or PRs if you'd like to see others included.

However, the much more pressing concern is with the documentation: if something isn't crystal clear, _please_ leave an issue or submit a suggested fix in order to make this as clear and descriptive as possible!
