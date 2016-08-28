<?php

namespace spec\SimoneS;

use BadMethodCallException;
use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use SimoneS\Lambda;

/**
 * 
 */
class LambdaSpec extends ObjectBehavior
{
            
    function it_throws_exception_on_empty_string()
    {
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringMake('');
    }
    
    function it_throws_exception_on_multiple_arrows()
    {
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringMake('foo => bar => baz');
    }
    
    function it_throws_exception_on_illegal_syntax()
    {
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringMake('$this is bad => syntax for sure');
    }
    
    function it_throws_exception_on_call_before_creating()
    {
        $this
            ->shouldThrow(BadMethodCallException::class)
            ->duringCall();
    }
            
    function it_returns_identity_lambda()
    {
        $argument = 10;
        
        $this
            ->make('$x => $x')
            ->shouldHaveType(Lambda::class)
            ->call([$argument])
            ;//->shouldBe($argument);
    }
    
    function it_forgives_up_to_three_arguments()
    {
        $arguments = [10, 20, 30];
        
        $this
            ->make('$x + $y + $z')
            ->shouldHaveType(Lambda::class)
            ->call($arguments)
            ;//->shouldBe(60);
    }
    
    function it_converts_to_plain_closure()
    {
        $this
            ->make('$x')
            ->getClosure()
            ->shouldHaveType(\Closure::class);
    }
    
    function it_is_callable()
    {
        $this
            ->make('$x')
            ->shouldBeCallable();
    }
    
    function it_allows_math_operations()
    {
        $operands       = [10, 20, 30, 40];
        $expectedResult = 25;
        
        $this
            ->make('$a, $b, $c, $d => $a + $b * $c / $d')
            ->call($operands)
            ->shouldBe($expectedResult);
    }
    
    function it_allows_object_attribute_getter()
    {
        $object = (object) ['name' => 'Lambda'];
        
        $this
            ->make('$obj => $obj->name')
            ->call([$object])
            ->shouldBe($object->name);
    }
    
    function it_allows_array_attribute_getter()
    {
        $array = ['name' => 'Lambda'];
        
        $this
            ->make('$array => $array["name"]')
            ->call([$array])
            ->shouldBe($array['name']);
    }
    
    function it_allows_string_concatenation()
    {
        $stringA = 'Hello ';
        $stringB = 'Lambda';
        
        $this
            ->make('$a, $b => $a . $b')
            ->call([$stringA, $stringB])
            ->shouldBe($stringA . $stringB);
    }
    
    function it_allows_function_call()
    {
        $this
            ->make('$x, $y => sprintf("%s, %s", $x, $y)')
            ->call(['Hello', 'Lambda'])
            ->shouldBe('Hello, Lambda');
    }
    
    function it_allows_complex_code()
    {
        $this
            ->make('$int, $string, $array => strval($int) . strtoupper($string) . $array[0]')
            ->call([100, ' times ', ['Lambda!']])
            ->shouldBe('100 TIMES Lambda!');
    }
    
    function it_accepts_definition_on_construction()
    {
        $argument = 'Lambda';
        
        $this->beConstructedWith('$x');
        
        $this
            ->call([$argument])
            ->shouldBe($argument);
    }
}
