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
    * @param string $blockName
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
    * Рендер
    *
    * @param string $layout
    * @param string $block
    *
    * @return void
    */     
    public function parseTpl()
    {
        if (!empty($this->html)) {
            return $this->execute($this->template);
        }
        

    }      
    
    /**
    * Наследование шаблона 
    *
    * @param string $blockName
    *
    * @return void
    */     
    protected function execute($template)
    {
        ob_start();
        extract($this->vareables);
        include_once $template;        
        return ob_get_clean();
    }     
}