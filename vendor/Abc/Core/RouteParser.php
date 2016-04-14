<?php

namespace ABC\Abc\Core;

/** 
 * Класс Router
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */   
class RouteParser
{
    /**
    * @var ABC\Abc\Core\Container
    */ 
    protected $container;
    
    /**
    * @var array
    */ 
    protected $config;
    
    /**
    * @var array
    */ 
    protected $defaultRoute;
    
    /**
    * @param object $container
    */ 
    public function __construct($container)
    {
        $this->container = $container;
        $this->config = $container->get('config'); 
    }     
    
    /**
    * 
    *
    * @param 
    *
    * @return 
    */    
    public function routeRule($uriHash)
    {
    
    }     
    
    /**
    * 
    *
    * @param 
    *
    * @return 
    */    
    public function c()
    {
    
    } 
}

