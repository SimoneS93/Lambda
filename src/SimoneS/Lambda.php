<?php

namespace SimoneS;

require_once __DIR__ . '/../../vendor/autoload.php';

use BadMethodCallException;
use Closure;
use InvalidArgumentException;

/**
 * Create closures from definition strings
 */
class Lambda
{
    /** @var Closure **/
    protected $closure;
    
    /**
     * 
     */
    public function __construct() {
        $this->closure = null;
    }
    
    public function __invoke($arguments = []) {
        return $this->call($arguments);
    }

    /**
     * Create closure
     * 
     * @param string $definition
     * @return Lambda
     * @throws InvalidArgumentException
     */
    public function make($definition)
    {
        if ( ! trim($definition)) {
            throw new InvalidArgumentException("Definition can't be empty");
        }
        
        $segments = preg_split('/\s*=>\s*/', $definition);
        
        // add implicit $x argument
        if (count($segments) === 1) {
            return $this->make("\$x = null, \$y = null, \$z = null => ({$definition})");
        }
        
        // actually create the closure
        if (count($segments) === 2) {
            list($arguments, $body) = $segments;
            
            $code = $this->createCode($arguments, $body);
            
            @eval($code);
            
            // something went wrong evaluating the code
            if ( ! isset($closure)) {
                throw new InvalidArgumentException("Bad syntax");
            }
            
            $this->closure = $closure;
            
            return $this;
        }
        
        throw new InvalidArgumentException("No more than one arrow operator is allowed");
    }
    
    /**
     * 
     * @param array $arguments
     * @return mixed
     */
    public function call(array $arguments = []) {
        if ( !is_callable($this->closure)) {
            throw new BadMethodCallException("The lambda doesn't hold a closure yet");
        }
        
        return call_user_func_array($this->closure, $arguments);
    }

    /**
     * Get underlying closure
     * @return \Closure
     */
    public function getClosure()
    {
        return $this->closure;
    }

    /**
     * 
     * @param string $arguments
     * @param string $body
     * @return string
     */
    private function createCode($arguments, $body)
    {
        return <<<CODE
            try { 
                \$closure = function({$arguments}) { 
                    return ({$body});
                };
            }
            catch (ErrorException \$ex) { 
                throw \InvalidArgumentException("Bad syntax");
            }
CODE;
    }
}
