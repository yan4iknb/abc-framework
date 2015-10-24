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
        
        $mysqli = Abc::gs('Mysqli');
        $mysqli->test = true;
        $mysqli->query("SELECT * FROM `test`");
        //$mysqli->query("sSELECT * FROM `test`");        
        Abc::dbg($mysqli); 
        
        //$pdo = Abc::gs('PDO');        
        //Abc::dbg($pdo);
    }
}













