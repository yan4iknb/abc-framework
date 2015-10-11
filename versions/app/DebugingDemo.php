<?php
namespace ABC\app;

use Exception;


class DebugingDemo
{  
    public function __construct()
    {
        $this->traceExample(1);
    }
    
    public function traceExample($var)
    {
        $var++;
        $this->errorExample($var);
    }    
    
    public function errorExample($var){
        echo $a;  
        //\ABC\Abc::current()->error('Пипец!');        
        //trigger_error('Пипец');
        //throw new Exception('Пипец!');
    }
}

