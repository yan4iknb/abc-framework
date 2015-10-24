<?php

namespace ABC\App\Controllers;

use ABC\Abc\Core\BaseController;

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
        new Example; 
    }
}










