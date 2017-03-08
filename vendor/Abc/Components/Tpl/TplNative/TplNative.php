<?php

namespace ABC\Abc\Components\Tpl\TplNative;

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
    
    protected $config;
    protected $tplName;
    protected $tplDir;
    protected $template;
    protected $data = [];
    
    /**
    * @param object $container
    */ 
    public function __construct($abc)
    {
        $this->abc = $abc;
        $this->config = $abc->getConfig(); 
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
        $this->tplDir   = str_replace('\\', ABC_DS, $this->config['template']['dir_template']);
        $tplName        = str_replace('\\', ABC_DS, $tplName);
        $this->template = $this->tplDir . $tplName .'.'. $this->config['template']['ext'];
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
        $layout = @$layout ?: $this->config['template']['layout'];
        $this->html = $this->execute($this->tplDir . $layout .'.'. $this->config['template']['ext']);
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
    * Разбор шаблона
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