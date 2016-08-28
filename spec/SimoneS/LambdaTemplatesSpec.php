<?php

namespace spec\SimoneS;

use Closure;
use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use SimoneS\Lambda;

class LambdaTemplatesSpec extends ObjectBehavior
{
    
    function it_returns_identity_lambda()
    {
        $argument = 'Lambda';
        
        $this
            ->identity()
            ->call([$argument])
            ->shouldBe($argument);
    }
    
    function it_returns_object_property_getter_lambda()
    {
        $object = (object) ['name' => 'Lambda'];
        
        $this
            ->property('name')
            ->call([$object])
            ->shouldBe($object->name);
    }
    
    function it_returns_object_method_getter_lambda_via_property()
    {
        $object = new Lambda('$x');
        
        $this
            ->property('getClosure()')
            ->call([$object])
            ->shouldHaveType(Closure::class);
    }
    
    function it_throws_exception_on_empty_property_getter()
    {
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringProperty('');
    }
    
    function it_throws_exception_on_bad_property_getter()
    {
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringProperty('illegal +attribute -name #@!');
    }
    
    function it_throws_exception_on_variable_property_getter()
    {
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringProperty('$attribute');
    }
    
    function it_returns_array_value_getter_lambda()
    {
        $array = ['name' => 'Lambda'];
        
        $this
            ->at('name')
            ->call([$array])
            ->shouldBe($array['name']);
    }
    
    function it_escapes_single_quoted_array_key()
    {
        $array = ["'complex key'" => 'Lambda'];
        
        $this
            ->at("'complex key'")
            ->call([$array])
            ->shouldBe($array["'complex key'"]);
    }
}
