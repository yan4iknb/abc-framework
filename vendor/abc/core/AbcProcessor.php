<?php

namespace ABC\abc\core;

use ABC\abc\core\Configurator;
use ABC\abc\core\ServiseLocator;

use ABC\abc\core\debugger\ErrorException;
use ABC\abc\core\debugger\php\PhpHandler;
use ABC\abc\core\debugger\loger\Loger;

/** 
 * Класс AbcFramework
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */   
class AbcProcessor
{
    /**
    * @var array
    */ 
    protected $userConfig;
    
    /**
    * @var object
    */ 
    protected $regestry;
    
    /**
    * @var object
    */ 
    protected $container;    
    
    /**
    * Конструктор
    * 
    */    
    public function __construct($userConfig = [])
    {
        $this->userConfig = $userConfig;
        $this->selectErrorMode();        
        $this->configurator = new Configurator(new ServiseLocator, $userConfig);
        $this->configureFramework();
    }
    
    /**
    * Выбирает режим обработки ошибок
    *
    * @return void
    */     
    protected function selectErrorMode()
    {
        if (empty($this->userConfig['debug_mod'])) {
            return false;
        } 
     
        if ($this->userConfig['debug_mod'] === 'display') {
            set_error_handler([$this, 'setException']);        
            new PhpHandler();
        } elseif ($this->userConfig['debug_mod'] === 'log')  {
            new Loger();
        }
    }
   
    /**
    * Бросает исключение на trigger_eror и отчеты интерпретатора
    *
    * @return void
    */
    public function setException($code, $message, $file, $line)
    { 
        if (error_reporting() & $code) {
            throw new ErrorException($message, $code, $file, $line);
        }
    }
    
    /**
    * Конфигурирует компоненты фреймворка
    *
    * @return void
    */     
    protected function configureFramework()
    {
        $this->container = $this->configurator->packComponents();
    } 
    
    /**
    * Выбирает и запускает компонент
    *
    * @return object
    */     
    public function getComponent($component = null)
    {    
        if (empty($component) || !is_string($component)) {
            trigger_error('Component name should be a string', E_USER_WARNING);
        }
        
        $object = $this->container->get($component);
        
        if (false === $object) {
            trigger_error('Component "'. $component .'" is not defined.', E_USER_WARNING);
        }
        
        return $object;
    }
    
    /**
    * Перезаписывает  компонент
    *
    * @param string $component
    * @param array $data
    *
    * @return object
    */      
    public function newComponent($component = null, $data = [])
    {    
        if (empty($component) || !is_string($component)) {
            trigger_error('Component name should be a string', E_USER_WARNING);
        }
            $this->locator->unsetServise($component);
            $class = '\ABC\Abc\components\\'. $component .'\\'. $component;
            $this->locator->set($component, function() use ($class, $data) {
                                                return new $class($data);
                                            }
                                );
        
        return $this->container->get($component);
    }
    
    /**
    * Перезаписывает глобальный компонент
    *
    * @param string $component
    * @param array $data
    *
    * @return object
    */     
    public function newGlobalComponent($component = null, $data = [])
    {    
        if (empty($component) || !is_string($component)) {
            trigger_error('Component name should be a string', E_USER_WARNING);
        }
            $this->locator->unsetServise($component);
            $class = '\ABC\Abc\components\\'. $component .'\\'. $component;
            $this->locator->setGlobal($component, function() use ($class, $data) {
                                                      return new $class($data);
                                                  }
                                      );
        
        return $this->container->get($component);
    }
}
