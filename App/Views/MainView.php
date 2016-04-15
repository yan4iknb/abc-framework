<?php

namespace ABC\App\Views;

use ABC\Abc\Core\BaseView;
use ABC\App\Example;

/** 
 * Класс MainView
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author irbis-team
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class MainView extends BaseView
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
