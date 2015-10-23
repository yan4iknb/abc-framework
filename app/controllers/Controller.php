<?php

namespace ABC\App\Controllers;

use ABC\Abc\Core\BaseController;

use ABC\Abc;
use ABC\App\Example;

/** 
 * Контролер
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */   
class Controller extends BaseController
{ 
    public function __construct()
    {
        $var1 = 'cодержимое первого аргумента';
        $var2 = ['первый' => 'cодержимое первого элемента',
                 'второй' => 'cодержимое второго элемента'
            ];
        
        echo $a;
     
        //Abc::dbg(); 
        //Abc::dbg($var1); 
        //Abc::dbg($var2);        
        //Abc::dbg(new \ABC\Abc); 
        //Abc::dbg('ABC\Abc');
     
        //new Example; 
        //$mysqli = Abc::getService('Mysqli');
        //echo $mysqli->connect_error;
        //Abc::dbg($db);
    }
}










