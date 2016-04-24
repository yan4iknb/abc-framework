<?php

namespace ABC\App\Models;

use ABC\Abc;

/** 
 * Класс MainModel
 * 
 */   
class MainModel
{
    public function getContent()
    {
        $db = Abc::getService('Mysqli');
        return ['hello' => 'Привет, Мир!'];
    }
}