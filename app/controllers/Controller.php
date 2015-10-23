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
        
        echo $a;
        //Abc::dbg($var1, $var2); 
        //Abc::dbg(); 
        //Abc::dbg($var1);         
        //Abc::dbg(new \ABC\Abc); 
        //Abc::dbg('ABC\Abc');
        //throw new \Exception('Первый аргумент - не число', E_USER_WARNING);
        //trigger_error('Полный пипец!!!'); 
        //$db = Abc::getService('Mysqli');
        //Abc::dbg($db);
    }
}