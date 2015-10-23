<?php

namespace ABC\Abc\Core;

/** 
 * Класс AbcFramework
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */   
class Router
{
    /**
    * @var ServiceLocator
    */
    public $locator;
    
    public $config;
    public $routes;
    
    protected $defaultSettings = [
                                    'application'     => 'app',
                                    'dir_controllers' => 'controllers',    
              ];

    /**
    * Вызывает контроллер
    *
    * @return void
    */        
    public function run()
    {
        $controllersDir = $this->getControllersDir();
        $controllerName = $this->getControllerName();
        $controller = '\ABC\\'. $controllersDir .'\\'. $controllerName .'Controller';
        
        if (!class_exists($controller)) {
            $this->create404($controller);
        } else {
            (new $controller());        
        }
    }
    
    /**
    * Возвращает директорию с пользовательскими контроллерами
    *
    * @return string
    */        
    public function getControllersDir()
    {
        $userSettings = @$this->config['settings'] ?: [];
        $settings = array_merge($this->defaultSettings, $userSettings);    
        return $settings['application'] .'\\'. $settings['dir_controllers'];
    }   
    
    /**
    * Возвращает имя вызванного контроллера
    *
    * @return string
    */        
    public function getControllerName()
    {   
        return null;
    }  
    
    /**
    * Возвращает имя вызванного контроллера
    *
    * @return string
    */        
    public function create404($controller)
    {   
        (new BaseController($this->config))->action404($controller);
    }  
}


















