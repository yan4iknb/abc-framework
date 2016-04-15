<?php

namespace ABC\Abc\Core;

use ABC\Abc\Core\BaseTemplate;

/** 
 * Класс Base
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class Base
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
    * Ошибка вызова метода
    *
    * @param string $method
    * @param mix $param
    *
    * @return void
    */     
    public function __call($method, $param)
    {
        $this->methodNotFound($method);
    }    
    
    /**
    * Возвращает объект модели
    *
    * @return array
    */ 
    protected function model()
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
    protected function setTpl($template)
    {
        if (method_exists($this->tpl, 'setTpl')) {
            $this->tpl->setTpl($template);        
        } else {
            $this->methodNotFound(__METHOD__);
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
    protected function assign($data, $value = null)
    {
        if (method_exists($this->tpl, 'assign')) {
            $this->tpl->assign($data, $value);       
        } else {
            $this->methodNotFound(__METHOD__);
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
    protected function assignHtml($data, $value = null)
    {
        if (method_exists($this->tpl, 'assignHtml')) {
            $this->tpl->assignHtml($data, $value);        
        } else {
            $this->methodNotFound(__METHOD__);
        } 
    }
    
    /**
    * Устанавливает блок 
    *
    * @param string $blockName
    *
    * @return void
    */     
    protected function setBlock($blockName)
    {
        if (method_exists($this->tpl, 'setBlock')) {
            $this->tpl->setBlock($blockName);        
        } else {
            $this->methodNotFound(__METHOD__);
        } 
    }
 
    /**
    * Очищает блок 
    *
    * @param string $blockName
    *
    * @return void
    */     
    protected function clearBlock($blockName)
    {
        if (method_exists($this->tpl, 'clearBlock')) {
            $this->tpl->clearBlock($blockName);        
        } else {
            $this->methodNotFound(__METHOD__);
        } 
    } 
    
    /**
    * Вывод шаблона в переменную
    *
    * @param string $blockName
    *
    * @return string
    */     
    protected function parseTpl()
    {
        if (method_exists($this->tpl, 'parseTpl')) {
            return $this->tpl->parseTpl($layout, $block);        
        } else {
            $this->methodNotFound(__METHOD__);
        } 
    } 
 
    /**
    * Наследование шаблона 
    *
    * @param string $blockName
    *
    * @return void
    */     
    protected function extendsTpl($block, $layout = null)
    {
        if (method_exists($this->tpl, 'extendsTpl')) {
            $layout = @$layout ?: $this->config['settings']['layout'];
            return $this->tpl->extendsTpl($block, $layout);        
        } else {
            $this->methodNotFound(__METHOD__);
        } 
    }     
    
    /**
    * Рендер
    *
    * @param string $layout
    * @param string $block
    *
    * @return void
    */     
    protected function display()
    {
        if (method_exists($this->tpl, 'display')) {
            $this->tpl->display();        
        } else {
            $this->methodNotFound(__METHOD__);
        }
    }
    
    /**
    * Сигнал об отсутствии метода
    *
    * @return void
    */  
    protected function methodNotFound($method)
    {
        $method = explode('::', $method);
        trigger_error(ABC_BAD_METHOD_CALL_EX . 
                     array_pop($method) .'() '. ABC_NO_METHOD, 
                     E_USER_WARNING);
    }
}