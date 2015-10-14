<?php

namespace ABC\abc\core;

/** 
 * Класс AbcFramework
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 * @package system.core 
 */   
class AbcFramework
{

/**
* Конструктор
* 
*/    
    public function __construct()
    {

    }
    
/**
* Запускает инсталлятор
* 
* @return string
*/      
    public function install()
    {
        return 'Вас приветствует ABC-Framework версии '. \ABC\Abc::getVersion();
    }
}

