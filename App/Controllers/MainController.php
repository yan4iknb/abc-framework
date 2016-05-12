<?php

namespace ABC\App\Controllers;

use ABC\Abc\Core\Base;

/** 
 * Контроллер
 * 
 */   
class MainController extends Base
{ 
    public function actionIndex()
    {
        $this->view->createHello();
    }
    
    public function actionSecond()
    {
        $this->view->createHello();
    }  
}










