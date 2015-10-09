<?php

namespace abc\core;

use components\AbcProfiler as AbcProfiler; 

/** 
 * Класс ErrorSelector
 * Выбирает способ обработки ошибок
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 * @package system.core 
 */   
 
class ErrorSelector
{
    

/**
 * Выбор способа реакции на ошибку
 *
 * Принимает параметрами сообщение об ошибке и её уровень 
 *
 * Имеет два режима, настраиваемых в конфигурационном файле ключем exception. 
 * Если настройка установлена в true или 1, то будет выброшено исключение. 
 * Если нет - управление передается селектору выбора обработчика
 *
 * @param string $message
 * @param int $errorLevel
 *
 * @return void
 */     
    public function selectDebugMod($message, $errorLevel)
    {
        if (!empty($this->config['exception'])) {
            set_exception_handler('setExceptionHandler');
            throw new Exception($message, $errorLevel);
        }
        
        $this->handlersSelector($message, $errorLevel);
    }
    
/**
 * Устанавливает способ обработки ошибок
 *
 * Имеет два режима, настраиваемых в конфигурационном файле ключем profiling. 
 * Установленная в true или 1, включает профилирование. В ином случае ошибки будет обрабатывать 
 * интерпретатор PHP
 * 
 * @param string $message
 * @param int $errorLevel
 *
 * @return void
 */    
    protected function handlersSelector($message, $errorLevel)
    {
        if (empty($this->config['profiling'])) {
            return (new AbcProfiler)->run($message, $errorLevel);
        }
        else {        
            set_error_handler('setAllException');
            trigger_error($message, $errorLevel);
        }
    }

}








    