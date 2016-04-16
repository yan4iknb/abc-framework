<?php

namespace ABC\App\Controllers;

use ABC\Abc\Core\BaseController;

/** 
 * Контроллер
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */   
class SecondController extends BaseController
{ 
    public function actionIndex()
    {
        ?>
        <br />
        <a href="<?=href('main/index'); ?>">Ссылка</a>
        <br />
        <?php
    }
}










