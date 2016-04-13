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
        if (method_exists($this->tpl, 'setTpl')) {
            $this->tpl->setTpl($template);        
        } else {
            trigger_error(ABC_BAD_METHOD_CALL_EX . 
                         __METHOD__ . ABC_NO_METHOD_IN_TPL, 
                         E_USER_WARNING);
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
        if (method_exists($this->tpl, 'assign')) {
            $this->tpl->assign($data, $value);       
        } else {
            trigger_error(ABC_BAD_METHOD_CALL_EX . 
                         __METHOD__ . ABC_NO_METHOD_IN_TPL, 
                         E_USER_WARNING);
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
        if (method_exists($this->tpl, 'assignHtml')) {
            $this->tpl->assignHtml($data, $value);        
        } else {
            trigger_error(ABC_BAD_METHOD_CALL_EX . 
                         __METHOD__ . ABC_NO_METHOD_IN_TPL, 
                         E_USER_WARNING);
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
        if (method_exists($this->tpl, 'setBlock')) {
            $this->tpl->setBlock($blockName);        
        } else {
            trigger_error(ABC_BAD_METHOD_CALL_EX . 
                         __METHOD__ . ABC_NO_METHOD_IN_TPL, 
                         E_USER_WARNING);
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
        if (method_exists($this->tpl, 'clearBlock')) {
            $this->tpl->clearBlock($blockName);        
        } else {
            trigger_error(ABC_BAD_METHOD_CALL_EX . 
                         __METHOD__ . ABC_NO_METHOD_IN_TPL, 
                         E_USER_WARNING);
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
        trigger_error(ABC_BAD_METHOD_CALL_EX . 
                     $method . ABC_NO_METHOD, 
                     E_USER_WARNING);
    }
}