<?php

namespace SimoneS;

use InvalidArgumentException;

/**
 * Collection of commonly used closures
 * 
 * @author SimoneS
 */

class LambdaTemplates
{
    
    /**
     * Identity lambda
     * 
     * @return Lambda
     */
    public function identity()
    {
        return lambda('$x');
    }
    
    /**
     * Array accessor lambda
     * 
     * @param mixed $key
     * @return Lambda
     */
    public function at($key)
    {
        $key = addslashes($key);
        
        return lambda("\$x['{$key}']");
    }

    /**
     * Object property getter lambda
     * 
     * @param string $property
     * @return Lambda
     * @throws InvalidArgumentException
     */
    public function property($property)
    {
        if ( ! preg_match('/[A-Za-z_]/', substr($property, 0, 1))) {
            throw new InvalidArgumentException("Bad property name");
        }
        
        return lambda("\$x->$property");
    }
}
