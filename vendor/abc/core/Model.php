<?php

namespace ABC\abc\core;

/** 
 * Базовая модель
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */   
abstract class Model
{ 
/**
 * Абстрактный метод для унификации данных моделей.
 *
 * @return array 
 */
    abstract protected function attributeNames();
    
}