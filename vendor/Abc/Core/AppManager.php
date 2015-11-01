<?php

namespace ABC\Abc\Core;

/** 
 * Класс AbcFramework
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */   
class AppManager
{
    /**
    * @var ServiceLocator
    */
    public $locator;
    public $request;
    public $config;
    
    protected $defaultSettings = [
                                    'application'     => 'App',
                                    'dir_controllers' => 'Controllers',    
              ];
    /**
    * Вызывает контроллер
    *
    * @return void
    */        
    public function run()
    {
        $controllersDir = $this->getControllersDir();
        $controller = $this->getController();
        $action = $this->getAction();
        $controller = '\ABC\\'. $controllersDir .'\\'. $controller;
        
        if (class_exists($controller)) {
         
            $objController = new $controller;
         
            if (method_exists($objController, $action)) {
                call_user_func([$objController, $action]);
            } else {
                $this->create404($action);
            }
            
        } else {
            $this->create404($controller);
        }
    }
    
    /**
    * Возвращает директорию с пользовательскими контроллерами
    *
    * @return string
    */        
    public function getControllersDir()
    {
        if (isset($this->config['settings'])) {
            $settings = array_merge($this->defaultSettings, $this->config['settings']);        
        } else {
            $settings = $this->defaultSettings;
        }
     
        return $settings['application'] .'\\'. $settings['dir_controllers'];
    }   
    
    /**
    * Возвращает имя вызванного контроллера
    *
    * @return string
    */        
    public function getController()
    {   
        $controller = $this->request->iniGET('controller');
        $controller = preg_replace('#[^a-z0-9\-_]#ui', '', $controller); 
        return mb_convert_case($controller, MB_CASE_TITLE) .'Controller';
    }  

    /**
    * Возвращает имя вызванного экшена
    *
    * @return string
    */        
    public function getAction()
    {   
        $action = $this->request->iniGET('action');
        $action = preg_replace('#[^a-z0-9\-_]#ui', '', $action);
        return 'action'. mb_convert_case($action, MB_CASE_TITLE);
    } 
    
    /**
    * Если не найден контроллер, активирует базовый с генерацией 404 заголовка
    *
    * @param string $controller
    *  
    * @return void
    */        
    public function create404($search)
    {   
        $baseController = new BaseController;
        $baseController->config = $this->config;
        $baseController->action404($search);
    }  
}


