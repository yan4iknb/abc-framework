<?php

namespace ABC\Abc\Core;

/** 
 * Класс Response
 * Предустановленные константы фреймворка
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */  

class Response
{

    public $contentEnable;    
    
    /**
    * @param object $abc
    */ 
    public function __construct($abc)
    {
        $this->contentEnable = $abc->getConfig('content_enable');
    } 
   
    /**
    * Отправляет контент в поток
    *
    * @param object $content
    * 
    * @return void
    */        
    public function sendContent($content)
    {
        if ($this->contentEnable) {
            echo $content;
        }
    }     
    
}
