<?php

namespace ABC\abc\core;

/** 
 * Класс Configurator
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */  
    
class Configurator
{
    /**
    * @var array
    */ 
    protected $userConfig;
    
    /**
    * @var ServiceLocator
    */ 
    protected $locator;
    
    /**
    * @var array
    */ 
    protected $registry;
    
    /**
    * Конструктор
    *
    * @param string $locator
    * @param array $userConfig
    */        
    public function __construct($locator, $userConfig)
    {
        $this->locator    = $locator;
        $this->userConfig = $userConfig;
        $this->registry   = include __DIR__ .'/../resourses/Components.php';
    } 
    
    /**
    * Упаковывает дефолтные компоненты в контейнер сервис-локатора
    *
    * @return object
    */     
    public function packComponents()
    { 
        foreach ($this->registry as $component => $type) {
         
            $class = '\ABC\Abc\components\\'. $component .'\\'. $component;
            $data  = $this->userConfig[$component] ?: [];
            
            if ($type == 'global' || true === $type) {
                $this->locator->setGlobal($component, 
                                          function() use ($class, $data) {
                                              return new $class($data);
                                          }
                );
            }
            else {
                $this->locator->set($component, 
                                    function() use ($class, $data) {
                                        return new $class($data);
                                    }
                );
            }
        }
        
        return $this->locator;        
    } 
    
    
    
    
    
    
    
}
