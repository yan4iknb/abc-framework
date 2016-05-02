<?php

namespace ABC\Abc\Core;

use ABC\Abc\Core\Base;

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
    * @var \ABC\Abc\Core\Abc
    */
    protected $abc;
    
    /**
    * @var \ABC\Abc\Core\Request
    */
    protected $request;
    

    protected $config;    
    protected $settings;
    
    /**
    * @param object $abc
    */ 
    public function __construct($abc)
    {  
        $this->abc = $abc;
        $this->config    = $abc->getFromStorage('config');
        $this->request   = $abc->getFromStorage('Request');
        $this->settings  = $this->config['settings'];
    }     
    
    /**
    * Вызывает контроллер  и, если есть, вьюшку и модель
    *
    * @return void
    */        
    public function run()
    {
        $controllersDir = $this->getControllersDir();
        $nameClass  = $this->getNameClass();
        $controller = '\ABC\\'. $controllersDir .'\\'. $nameClass .'Controller';
        $action     = $this->getAction();
     
        if (class_exists($controller)) {
            $objController = new $controller($this->config);
          
            if (method_exists($objController, $action)) {
                $viewsDir = $this->getViewsDir();
                $view = '\ABC\\'. $viewsDir .'\\'. $nameClass .'View';
                
                $modelsDir = $this->getModelsDir();
                $model = '\ABC\\'. $modelsDir .'\\'. $nameClass .'Model';
                
                if (class_exists($view)) {
                    $objView = new $view;
                    $objView->model = class_exists($model) ? new $model : null;
                } else {
                    $objView = new Base;  
                }
             
                $objView->abc       = $this->abc;
                $objView->config    = $this->config;
                
                $objController->abc       = $this->abc;
                $objController->view      = $objView;
                $objController->config    = $this->config;
                
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
        return $this->settings['application'] .'\\'. $this->settings['dir_controllers'];
    } 
    
    /**
    * Возвращает директорию с пользовательскими вьюшками
    *
    * @return string
    */        
    public function getViewsDir()
    {
        return $this->settings['application'] .'\\'. $this->settings['dir_views'];
    }
    
    
    /**
    * Возвращает директорию с пользовательскими вьюшками
    *
    * @return string
    */        
    public function getModelsDir()
    {
        return $this->settings['application'] .'\\'. $this->settings['dir_models'];
    }
    
    /**
    * Возвращает имя вызванного контроллера
    *
    * @return string
    */        
    public function getNameClass()
    {   
        $nameClass = $this->request->getController();
        $nameClass = preg_replace('#[^a-z0-9\-_]#ui', '', $nameClass); 
        return mb_convert_case($nameClass, MB_CASE_TITLE);
    }  

    /**
    * Возвращает имя вызванного экшена
    *
    * @return string
    */        
    public function getAction()
    {   
        $action = $this->request->getAction();
        $action = preg_replace('#[^a-z0-9\-_]#ui', '', $action);
        return 'action'. mb_convert_case($action, MB_CASE_TITLE);
    } 
 
    /**
    * Если не найден контроллер или экшен, активирует 
    * базовый контроллер с генерацией 404 заголовка
    *
    * @param string $controller
    *  
    * @return void
    */        
    public function create404($search)
    {   
        $baseController = new Base($this->config);
        $baseController->action404($search);
    }  
}


