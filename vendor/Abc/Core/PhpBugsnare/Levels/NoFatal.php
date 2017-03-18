<?php

namespace ABC\Abc\Core\PhpBugsnare\Levels;

use ABC\Abc\Core\PhpBugsnare\Handler;

/** 
 * Class Handler
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author Nikolay Twin
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 * 
 */   
class NoFatal extends Handler
{

    public function __construct($config) 
    {
        parent::__construct($config);
        set_exception_handler([$this, 'exceptionHandler']);
        set_error_handler([$this, 'triggerErrorHandler']);
    }
    
    /**
    * Catch exceptions
    *
    * @return void
    */   
    public function exceptionHandler($e) 
    {
        $trace = $e->getTrace();
        $this->message   = $e->getMessage();
        $this->code      = $e->getCode();      
        $this->backTrace = $this->prepareTrace($trace);  
        $this->createReport();  
    }
    
    /**
    * Catch trigger_error
    *
    * @return void
    */   
    public function triggerErrorHandler($code, $message, $file, $line) 
    {
        if (error_reporting() & $code) {
            $this->exception = false;
            
            $this->message = $message;
            $this->code = $code; 
            $this->file = $file;
            $this->line = $line; 
            $trace = debug_backtrace();
         
            if (in_array($code, $this->E_Lavel)) {
                array_shift($trace);            
            }
         
            $trace = $this->prepareTrace($trace);
            $this->backTrace = array_reverse($trace);
            $this->createReport();          
        } 
    }
}
