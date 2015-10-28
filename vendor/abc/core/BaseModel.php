<?php

namespace ABC\Abc\Core;

/** 
 * Базовая модель
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */   
abstract class BaseModel
{ 
    /**
    * Абстрактный метод для унификации возвращаемых данных моделей.
    *
    * @return array 
    */
    abstract protected function getAattribute();
    
}