<?php

use SimoneS\Lambda;
use SimoneS\LambdaTemplates;

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

if ( !function_exists('lambdaT')) {
    
    /**
     * Create a lambda from template name
     * 
     * @param string $template
     * @param array $arguments
     * @return Lambda
     */
    function lambdaT($template, array $arguments = [])
    {
        $lambdaTemplates = new LambdaTemplates();
        
        if ( !method_exists($lambdaTemplates, $template)) {
            throw new InvalidArgumentException("Template [{$template}] doesn't exist");
        }
        
        return call_user_func_array([$lambdaTemplates, $template], $arguments);
    }
}
