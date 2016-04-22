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
    /**
    * @var \ABC\Abc\Core\Container
    */
    protected $container;

    /**
    * @var array
    */ 
    protected $config;    
    
    /**
    * @param object $container
    */ 
    public function __construct($container)
    {
        $this->container = $container;
        $this->config = $container->get('config');
    } 
        
    /**
    * Отправляет контент в поток
    *
    * @return string
    */        
    public function sendContent($content)
    {
        if ($this->config['content_enable']) {
            echo $content;
        }
    }     
    
}
