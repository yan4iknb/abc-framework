<?php

namespace ABC\App\Controllers;

use ABC\Abc;
use ABC\Abc\Core\BaseController;
use ABC\App\Example;

/** 
 * Контроллер
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */   
class MainController extends BaseController
{ 
    public function actionIndex()
    {
        $this->view->createHello();
        $this->display();
    }
}










