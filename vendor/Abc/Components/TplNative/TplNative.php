<?php

namespace ABC\Abc\Components\TplNative;

use ABC\Abc\Core\Exception\AbcError;

/** 
 * Класс BaseView
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class TplNative
{   
    
    protected $TplConfig;
    protected $tplName;
    protected $tplDir;
    protected $template;
    protected $data = [];
    
    /**
    * @param object $container
    */ 
    public function __construct($abc)
    {
        $this->TplConfig = $abc->getConfig('template'); 
    }
    
    /**
    * Устанавливает шаблон
    *
    * @param string|array $data
    * @param mix $value
    *
    * @return void
    */     
    public function selectTpl($tplName)
    {
        $this->tplDir   = str_replace('\\', ABC_DS, $this->TplConfig['dir_template']);
        $tplName        = str_replace('\\', ABC_DS, $tplName);
        $this->template = $this->tplDir . $tplName .'.'. $this->TplConfig['ext'];
        return $this;
    } 
    
    /**
    * Передает переменные в шаблон
    *
    * @param string|array $data
    * @param mix $value
    *
    * @return $this
    */     
    public function assign($data, $value = null)
    {
        if (is_array($data)) {
            $this->data = array_merge($data, $data);
        } else {
            $this->data[$data] = $value;
        }
        
        return $this;
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
        if (is_array($data)) {
            $this->data = array_merge($this->data, $data);
        } else {
            $this->data[$data] = htmlChars($value);
        }
        
        return $this;
    }
    
    /**
    * Наследование шаблона 
    *
    * @param string $block
    * @param string $layout
    *
    * @return void
    */     
    public function extendsTpl($block, $layout = null)
    {
        $template = $this->execute($this->template);
        $this->assign($block, $template);
        $layout = @$layout ?: $this->TplConfig['layout'];
        $this->html = $this->execute($this->tplDir . $layout .'.'. $this->TplConfig['ext']);
        return $this;
    }  
    
    /**
    * Возвращает заполненный шаблон
    *
    * @return string
    */     
    public function parseTpl()
    {
        if (!empty($this->html)) {
            return $this->execute($this->template);
        }
        
        return $this->html;
    }  
    
    /**
    * Returns the content
    * 
    * @return string
    */
    public function getContent()
    {        
        return $this->html;
    }  
    
    /**
    * Эмуляция наследования 
    *
    * @param string $template
    *
    * @return string
    */     
    protected function execute($template)
    {
        ob_start();
        extract($this->data);
        include_once $template;        
        return ob_get_clean();
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
        $method = explode('::', $method);
        AbcError::badMethodCall('Native Template: '. array_pop($method) .'() '. ABC_NO_METHOD);
    } 
}