<?php

namespace ABC\App\Views;

use ABC\Abc;
use ABC\Abc\Core\Base;

/** 
 * Класс MainView
 *  
 */   
class MainView extends Base
{
    public function createHello()
    {  
        $content = $this->model->getContent();
        echo $content['hello']; Abc::getService('ttt');;
    }
}
