<?php

namespace ABC\Abc\Services\Uri\Router;


/** 
 * Класс DefaultRouter
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */   
class Router
{
    protected $config;
    
    /**
    * @param object $abc
    */ 
    public function __construct($abc)
    {   
        $this->config = $abc->getConfig('router');
    }
    
    /**
    * 
    */ 
    public function __call($method, $params)
    {
        if ($this->config['type'] === 'closure') {
            return (new Executor($this->config))->$method($params[0]);
        } else {
            return (new Custom($this->config))->$method($params[0]);
        }
    }
}
