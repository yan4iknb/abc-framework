<?php

namespace ABC\App\Controllers;

use ABC\Abc;
use ABC\Abc\Core\BaseController;
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
        $var2 = ['первый' => 'cодержимое второго аргумента'];
        $var3 = 1;
        
        //echo $a;
 
        //Abc::dbg(); 
        //Abc::dbg($var1); 
        //Abc::dbg($var2);        
        //Abc::dbg(new \ABC\Abc); 
        //Abc::dbg('ABC\Abc');

        //new Example; 
        $db = Abc::getService('Mysqli');
        Abc::dbg($db);
    }
}


class Example
{ 
    public function __construct()
    {        
       throw new \Exception('Первый аргумент - не число');
        //trigger_error('Полный пипец!!!'); 
    }
}










