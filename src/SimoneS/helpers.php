<?php

use SimoneS\Lambda;

/* 
 * Collection of helpers to the most common actions
 */

if ( !function_exists('lambda')) {
    
    /**
     * Create a lambda
     * 
     * @param string $definition
     * @return Lambda
     */
    function lambda($definition)
    {
        return new Lambda($definition);
    }
}

