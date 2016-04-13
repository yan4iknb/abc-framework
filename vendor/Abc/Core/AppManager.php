<?php

namespace ABC\Abc\Core;

use ABC\Abc;
use ABC\Abc\Core\BaseModel;
use ABC\Abc\Core\BaseView;
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
    public $request;
    public $config;

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
            $objController->tpl = $this->getTemplate();
          
            if (method_exists($objController, $action)) {
                $viewsDir = $this->getViewsDir();
                $view = '\ABC\\'. $viewsDir .'\\'. $nameClass .'View';
                
                $modelsDir = $this->getModelsDir();
                $model = '\ABC\\'. $modelsDir .'\\'. $nameClass .'Model';
                
                if (class_exists($view)) {
                    $objView = new $view;
                    $objView->model = class_exists($model) ? new $model : null;
                } else {
                    $objView = new BaseView;
                    
                }
                
                $objView->config = $this->config;
                $objView->tpl = $this->getTemplate();   
                $objController->view  = $objView;
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
        return $this->config['settings']['application'] .'\\'. $this->config['settings']['dir_controllers'];
    } 
    
    /**
    * Возвращает директорию с пользовательскими вьюшками
    *
    * @return string
    */        
    public function getViewsDir()
    {
        return $this->config['settings']['application'] .'\\'. $this->config['settings']['dir_views'];
    }
    
    
    /**
    * Возвращает директорию с пользовательскими вьюшками
    *
    * @return string
    */        
    public function getModelsDir()
    {
        return $this->config['settings']['application'] .'\\'. $this->config['settings']['dir_models'];
    }
    
    /**
    * Возвращает имя вызванного контроллера
    *
    * @return string
    */        
    public function getNameClass()
    {   
        $nameClass = $this->request->iniGET('controller', 'main');
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
        $action = $this->request->iniGET('action', 'index');
        $action = preg_replace('#[^a-z0-9\-_]#ui', '', $action);
        return 'action'. mb_convert_case($action, MB_CASE_TITLE);
    } 
    
    /**
    * Возвращает объект шаблонизатора
    *
    * @return string
    */        
    public function getTemplate()
    {   
        return Abc::getService('Template');
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
        $baseController = new BaseController($this->config);
        $baseController->action404($search);
    }  
}


