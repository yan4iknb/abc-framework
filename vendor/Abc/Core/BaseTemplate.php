<?php

namespace ABC\Abc\Core;

/** 
 * Класс BaseView
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class BaseTemplate
{ 
    /**
    * @var ABC\Abc\Core\Container
    */ 
    protected $container;    
    
    protected $config;
    protected $tplName;
    protected $tplDir;
    protected $template;
    protected $vareables = [];
    
    /**
    * @param object $container
    */ 
    public function __construct($container)
    {
        $this->container = $container;
        $this->config = $container->get('config'); 
    }
    
    /**
    * Устанавливает шаблон
    *
    * @param string|array $data
    * @param mix $value
    *
    * @return void
    */     
    public function setTpl($tplName)
    {
        $this->tplDir   = str_replace('\\', ABC_DS, $this->config['settings']['dir_template']);
        $this->template = $this->tplDir . str_replace('\\', ABC_DS, $tplName) .'.tpl';
        return $this;
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
        if (is_array($data)) {
            $this->vareables = array_merge($this->data, $data);
        } else {
            $this->vareables[$data] = $value;
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
            $this->vareables = array_merge($this->data, $data);
        } else {
            $this->vareables[$data] = htmlChars($value);
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
        $layout = @$layout ?: $this->config['settings']['layout'];
        $this->html = $this->execute($this->tplDir . $layout .'.tpl');
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
        extract($this->vareables);
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
        AbcError::badMethodCall(array_pop($method) .'() '. ABC_NO_METHOD);
    } 
}