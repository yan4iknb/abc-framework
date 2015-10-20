<?php

namespace ABC\app;

use \ABC\abc as Abc;

class TestDebug
{
    public function __construct($var1, $var2)
    {
        //echo $a;
        //Abc::dbg($var1, $var2); 
        //Abc::dbg(); 
        //Abc::dbg($var1);         
        //Abc::dbg(new TraceExample); 
        //Abc::dbg('ABC\Abc');
        //throw new \Exception('Первый аргумент - не число', E_USER_WARNING);
        //trigger_error('Полный пипец!!!'); 
     
        $mysqli = Abc::component('MySQLi');
        Abc::dbg($mysqli);
    }
}


class DebugingDemo
{  
    public function __construct()
    {
        $var1 = 'cодержимое первого аргумента';
        $var2 = ['первый' => 'cодержимое второго аргумента'];
        $var3 = 1;
        
        new traceExample1($var1, $var2);
    }
}   

class TraceExample
{
    protected $property;

    public function __construct()
    {
       
    }  
}

class TraceExample1
{  
    public function __construct($var1, $var2)
    {
        new traceExample2($var1, $var2);
    }  
}

class TraceExample2
{      
    public function __construct($var1, $var2)
    {
        new TestDebug($var1, $var2);
    }
}
