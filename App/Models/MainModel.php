<?php

namespace ABC\App\Models;

use ABC\Abc\Core\BaseModel;
/** 
 * Класс MainView
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author irbis-team
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class MainModel extends BaseModel
{
    public function getAattribute()
    {
        return ['hello' => 'Привет, Мир!'];
    }
}