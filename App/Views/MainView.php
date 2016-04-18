<?php

namespace ABC\App\Views;

use ABC\Abc\Core\Base;
use ABC\App\Example;

/** 
 * Класс MainView
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author irbis-team
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class MainView extends Base
{

    public function createHello()
    {
        $this->setTpl('main');
        $hello = $this->model()->getAattribute()['hello'];
        $this->assignHtml('hello', $hello);
        $this->setBlock('hello');
        $this->extendsTpl('content')->display();
    }
}
