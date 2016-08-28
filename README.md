# Lambda

Create closures from definition strings.


## Install

Install tha package via composer:

```sh
composer require simones/lambda
```


## How to use

You generate closures passing a definition string to `Lambda::make`: this will return a `Lambda` instance, 
that you can call directly (it implements the `__invoke` magic method) or via the `call` method, 
passing an optional array of arguments.


## Defition strings format

The `make` method accepts definitions in the format `arguments => body`, borrowed from languages like Coffescript. 
So, for example, the following

```php
$identity = $lambda->make('$x => $x')
```
will translate in something like:

```php
$identity = function($x) { return $x; };
```

Multiple arguments are allowed and complex expressions can be used as the body.
If the arguments part is not present, the variables `x, y, z` will be automatically injected and defaulted to `NULL`; 
so the above code could be written as:

```php
$lambda->make('$x')
```

You're allowed to use as many parameters as wanted and arbitrary complex body definitions, 
though I recommend you to use mainly for short anonymous functions 
(I wrote this to be used with Laravel Collection's `map` and `filter` methods).

Here's a spec case that tests the use with complex code:

```php
function it_allows_complex_code()
    {
        $this
            ->make(
                '$int, $string, $array => ' .
                'strval($int) . strtoupper($string) . $array[0]')
            ->call([100, ' times ', ['Lambda!']])
            ->shouldBe('100 TIMES Lambda!');
    }
```


## Limits

Keep in mind that, at this stage, no syntax check is done on the code, so you should input only trusted 
and well-formed definitions.
It just wraps your code in a function definition, adds minor structure controls and throws exceptions 
when it can't do its job.
I added a spec suite to test the intended behaviour, but it's my first package and I don't really feel 
it should be used in production as is.


## Bugs and improvements

Every help is welcome to improve the package: just create a pull request.