<?php

namespace ABC\Abc\Core;

use ABC\Abc\Components\Template\Template;

/** 
 * Класс MainView
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class BaseView
{
    /**
    * @var array
    */     
    public $config;

    /**
    * @var Model
    */     
    public $model;
    
    /**
    * @var Template
    */  
    public $tpl;    
  
    /**
    * Возвращает объект модели
    *
    * @return array
    */ 
    public function model()
    {
        if (is_object($this->model)) {
            return $this->model;       
        } 
     
        trigger_error(ABC_BAD_METHOD_CALL_EX . 
                      ABC_NO_MODEL, 
                      E_USER_WARNING);
    }
    
    /**
    * Устанавливает шаблон
    *
    * @param string|array $data
    * @param mix $value
    *
    * @return void
    */     
    public function setTpl($template)
    {
        $this->checkTemplate(); 
     
        if (method_exists($this->tpl, 'setTpl')) {
            $this->tpl->setTpl($template);        
        } else {
            $this->notFound($method);
        }
    } 
    
    /**
    * Передает переменные в шаблон
    *
    * @param string|array $data
    * @param mix $value
    *
    * @return void
    */     
    public function assign($data, $value = null)
    {
        $this->checkTemplate(); 
     
        if (method_exists($this->tpl, 'assign')) {
            $this->tpl->assign($data, $value);       
        } else {
            $this->notFound($method);
        }  
    } 
    
    /**
    * Передает переменные в шаблон для вывода в поток 
    *
    * @param string|array $data
    * @param mix $value
    *
    * @return void
    */     
    public function assignHtml($data, $value = null)
    {
        $this->checkTemplate(); 
     
        if (method_exists($this->tpl, 'assignHtml')) {
            $this->tpl->assignHtml($data, $value);        
        } else {
            $this->notFound($method);
        } 
    }
    
    /**
    * Устанавливает блок 
    *
    * @param string $blockName
    *
    * @return void
    */     
    public function setBlock($blockName)
    {
        $this->checkTemplate(); 
     
        if (method_exists($this->tpl, 'setBlock')) {
            $this->tpl->setBlock($blockName);        
        } else {
            $this->notFound($method);
        } 
    }
 
    
    /**
    * Очищает блок 
    *
    * @param string $blockName
    *
    * @return void
    */     
    public function clearBlock($blockName)
    {
        $this->checkTemplate(); 
     
        if (method_exists($this->tpl, 'clearBlock')) {
            $this->tpl->clearBlock($blockName);        
        } else {
            $this->notFound($method);
        } 
    } 
    
    /**
    * Ошибка вызова метода
    *
    * @param string $method
    * @param mix $param
    *
    * @return void
    */     
    public function __call($method, $param)
    {
        $this->notFound($method);
    }
    
    /**
    * Проверка включения шаблонизатора
    *
    * @return void
    */  
    protected function checkTemplate()
    {
        if (false === $this->tpl) {
            trigger_error(ABC_DOMAIN_EX . 
                         ABC_TPL_DISABLE, 
                         E_USER_WARNING);
        }
    }
 
    /**
    * Проверка наличия метода
    *
    * @return void
    */  
    protected function notFound($method)
    {
        trigger_error(ABC_BAD_METHOD_CALL_EX . 
                     $method . ABC_NO_METHOD, 
                     E_USER_WARNING);
    }
}