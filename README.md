# Lambda

Create closures from definition strings.


## Install

```sh
composer require simones/lambda
```


## How to use

You generate closures passing a definition string to the `Lambda` constructor or to `Lambda::make`: 
they will return a `Lambda` instance, that you can call directly (it implements the `__invoke` magic method) 
or via the `call` method, passing an optional array of arguments.


## Defition strings format

Definitions are in the format `arguments => body`, borrowed from languages like Coffescript. 
So, for example, the following

```php
$increment = new Lambda('$x => $x + 1');
```
will translate in something like:

```php
$increment = function($x) { return ($x + 1); };
```

Multiple arguments are allowed and complex expressions can be used as the body.
If the arguments part is not present, the variables `x, y, z` will be automatically injected and defaulted to `NULL`; 
so the above code could be written as:

```php
$increment = new Lmabda('$x + 1');

// or with the lambda helper

$increment = lambda('$x + 1');

// and translates to

$increment = function($x = NULL, $y = NULL, $z = NULL) { return ($x + 1); }
```

You're allowed to use as many parameters as wanted and arbitrary complex body definitions, 
though I recommend you to use mainly for short anonymous functions 
(I wrote this to be used with Laravel Collection's `map` and `filter` methods).

Here's a spec case that tests the use with complex code:

```php
function it_allows_complex_code()
    {
        $this
            ->make('$int, $string, $array => strval($int) . strtoupper($string) . $array[0]')
            ->call([100, ' times ', ['Lambda!']])
            ->shouldBe('100 TIMES Lambda!');
    }
```


## Limits

At this stage, no syntax check is done on the code, so you should input only trusted 
and well-formed definitions.
It just wraps your code in a function definition, adds minor structure controls and throws exceptions 
when it can't do its job.
I added a spec suite to test the intended behaviour, but it's my first package and I don't really feel 
it should be used in production as is.


## Bugs and improvements

Every help is welcome to improve the package: just create a pull request.