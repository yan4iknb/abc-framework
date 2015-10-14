<?php

namespace ABC\abc\components\debugger\loger;

use ABC\abc\components\debuger\ExceptionHandler as ExceptionHandler;

/** 
 * Класс Loger 
 * Логирует ошибки скриптов
 * для корректного выброса исключения
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 * @package system.cmponents.loger 
 */  
class Loger extends ExceptionHandler 
{
 
    protected function getLocation()
    {
        echo 'Логер не реализован (((';    
    }
    protected function getTrace()
    {
    
    }
    protected function createCode()
    {
    
    } 
    protected function createTrace()
    {
    
    }
    
    protected function action()
    {
    
    }

}