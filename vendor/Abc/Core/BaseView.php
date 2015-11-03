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
    * Конструктор
    *
    * @param string $config
    */  
    public function __construct($config)
    {
        $this->config = $config;  
    }
    
    /**
    * Возвращает данные из модели
    *
    * @return array
    */ 
    public function getAattribute()
    {
        return $this->model->getAattribute();
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
        $this->tpl->setTpl($template);  
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
        $this->tpl->assign($data, $value);  
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
        $this->tpl->assignHtml($data, $value);  
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
        $this->tpl->setBlock($blockName);  
    }  

}