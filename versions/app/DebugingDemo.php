<?php

namespace ABC\app;

class DebugingDemo
{  
    public function __construct()
    {
        $var1 = 'cодержимое первого аргумента';
        $var2 = 'cодержимое второго аргумента';
       
        echo $a; // Notice
        
        $this->traceExample1($var1, $var2);
    }
    
    public function traceExample1($var1, $var2)
    {
        $this->traceExample2($var1, $var2);
    }    
    
    public function traceExample2($var1, $var2)
    {
        (new ExampleComponent($var1, $var2));
    }
}








// Эмуляция компонента, выбрасывающего исключение

class ExampleComponent
{  
    public function __construct($var1, $var2)
    {
        if (!is_int($var1)) {
            throw new \Exception('Первый аргумент - не число', E_USER_WARNING);
            //trigger_error('Полный пипец!!!');        
        }

    }

}










