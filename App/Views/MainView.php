<?php

namespace ABC\App\Views;

use ABC\Abc;
use ABC\Abc\Core\Base;

class MainView extends Base
{
    public function createHello()
    {  
        $this->selectTpl('main');
        $data = $this->model->getContent();
        $this->tpl->assignHtml($data);
        $this->tpl->setBlock('hello');
        $this->tpl->extendsTpl('content');
        $this->render();
    }
}
