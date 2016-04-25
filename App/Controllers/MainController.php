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
    {echo $a;
        $this->view->createHello();
    }      
}










