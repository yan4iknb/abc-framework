<?php

namespace ABC\App;

use ABC\Abc;

class Example
{ 
    public function __construct()
    { 
        $var1 = 'cодержимое первого аргумента';
        $var2 = ['первый' => 'cодержимое первого элемента',
                 'второй' => 'cодержимое второго элемента'
            ];

               
        //echo $a;
     
        //Abc::dbg(); 
        //Abc::dbg($var1); 
        //Abc::dbg($var2);        
        //Abc::dbg(new \ABC\Abc); 
        //Abc::dbg('ABC\Abc');
        //throw new \Exception('Тестовое исключение');
        //trigger_error('Полный пипец!!!');         
        
        //$mysqli = Abc::gs('Mysql');
        //$mysqli->test = true;
        //$mysqli->query("SELECT * FROM `test`");
        
        //$mysqli->query("sSELECT * FROM `test`");        
        
        //////////////////////////////
        // Демонстрация IOC
        
        $ioc = Abc::gs('DiC');
        
        $ioc->set('dependence',
                   function() {
                   return new Example1;
                   }
               );    
     
        $ioc->set('service',
                   function() {
                   return new Example2;
                   }
               );              
    
        $ioc->injection('service', 'dependence', 'newSerwice', ['var' => 'Hello, World!']);
        
        
        $obj = $ioc->get('newSerwice');
        $obj->run();
        /////////////////////////////////////////////////
    }
}

// Классы для демонстрации IOC
class Example1
{
    public function display($var)
    {
        echo $var;
    }
}

class Example2
{
    public $var;
    protected $dep;
    
    public function __construct($dep = null)
    {
        if (is_object($dep)) {
            $this->dep = $dep;
        }
    }
    
    public function run()
    {
        $this->dep->display($this->var);
    }    
}










