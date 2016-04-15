<?php

namespace ABC\App;

use ABC\Abc;

/**
* Класс демонстрации текущих возможностей.
* Раскомменчивайте блоки и смтрите что будет.
*/    

class Example
{ 
    public function __construct()
    { 
        $var1 = 'cодержимое первого аргумента';
        $var2 = ['первый' => 'cодержимое первого элемента',
                 'второй' => 'cодержимое второго элемента',
                 3 => new \ABC\Abc
            ];
        $var3 = ['cодержимое первого элемента',
                 'cодержимое второго элемента'
            ];
        //- - - - - - - - - - - - - - - - - - - - - - - 
        // Демонстрация дебаггера  (раскомменчивать по очереди)
        
        pow(2, 3, 5);
        
        //echo DDDDD;
        //echo $a;
        //dbg(); 
        //dbg($var1); 
        //dbg($var2['третий']);
        //dbg($var3[2]); 
        //dbg(new \ABC\Abc); 
        //dbg('ABC\Abc');
        //throw new \Exception('Тестовое исключение');
        //trigger_error(ABC_INVALID_ARGUMENT_EX .'Полный пипец!!!', E_USER_WARNING);
        
        // Конец - - - - - - - - - - - - - - - - - - - -   
        
        
        
        //- - - - - - - - - - - - - - - - - - - - - - - 
        // Простые запросы mysqli
/*
       
        $mysqli = getService('Mysqli');
        $mysqli->test = true;
        $mysqli->query("SELECT * FROM `test`");
        
*/
        // Конец - - - - - - - - - - - - - - - - - - - -
        
        
        
        //- - - - - - - - - - - - - - - - - - - - - - - 
        // Продготовленные запросы  mysqli
/* 
        $mysqli = getService('Mysqli');
        $mysqli->test();
        $stmt = $mysqli->prepare("INSERT INTO `test` VALUES (?, ?)");
        
        $stmt->bind_param('isr', $id, $text);
        $id = 1;  
        $text = "te'st";        
        
        $stmt->execute();
        
*/
        // Конец - - - - - - - - - - - - - - - - - - - -        
        
     
        
        //- - - - - - - - - - - - - - - - - - - - - - - 
        // Простые запросы  PDO
        
/*         
        $pdo = getService('PDO');
        $pdo->test();
        
        $stmt = $pdo->query("SELECT * FROM `test`");  
*/
        // Конец - - - - - - - - - - - - - - - - - - - - 
        
        
        
        //- - - - - - - - - - - - - - - - - - - - - - - 
        // Подготовленные запросы  PDO
/*       

        $pdo = getService('PDO');
        
        $pdo->test();

        $stmt = $pdo->prepare("SELECT * FROM test.tests WHERE `id` = ? AND `text` = ?");
        $stmt->execute([1, "te'xt"]);
        
*/     
        
        // Конец - - - - - - - - - - - - - - - - - - - - 
        
        
        
        //- - - - - - - - - - - - - - - - - - - - - - - 
        // Демонстрация IOC
        /*
        
        $ioc = getService('DiC');
      
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
    
        $ioc->injection('dependence', 'service', 'newSerwice', ['var' => 'Hello, World!']);        
        $obj = $ioc->get('newSerwice');
        $obj->run();
        */
        // Конец - - - - - - - - - - - - - - - - - - - -
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









