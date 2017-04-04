<?php

namespace ABC\Abc\Services;

use ABC\Abc\Core\Exception\AbcError;

/** 
 * Класс Builder
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class Builder 
{
    protected $serviceId; 
    protected $container;
    protected $dir;
    protected $subDir = [
        'DbCommand'     => 'Sql',
        'Mysqli'        => 'Sql',
        'Pdo'           => 'Sql',
        'SqlDebug'      => 'Sql',
        'Template'      => 'Tpl',
        'TplNative'     => 'Tpl',
        'Router'        => 'Uri',
        'UriManager'    => 'Uri',
    ];
    
    public function __construct($serviceId, $abc)
    {
        $this->serviceId = $serviceId;
        $this->abc = $abc;
        $this->container = $abc->getContainer();
        $this->dir = !empty($this->subDir[$serviceId]) ? $this->subDir[$serviceId] .'\\' : null;
    }
    
    /**
    * Получает сервис из локатора если он есть
    * или сначала помещает его туда
    *
    * @return object
    */    
    public function newService()
    {  
        if (!$this->container->checkService($this->serviceId)) {
            $this->buildService();
        }
        
        return $this->container->getNew($this->serviceId);
    }
    
    /**
    * Получает сервис из локатора если он есть
    * или сначала помещает его туда
    * (по принципу Singleton)
    *
    * @return object
    */    
    public function sharedService()
    { 
        if (!$this->container->checkService($this->serviceId)) { 
            $this->buildService(true);
        }
      
        return $this->container->get($this->serviceId);
    } 
    
    /**
    * Строит сервис.
    * 
    * @param bool $global
    *
    * @return void
    */         
    protected function buildService($global = false)
    {
        $abc = $this->abc;    
        $component = __NAMESPACE__ .'\\'. $this->dir . $this->serviceId .'\\'. $this->serviceId;   
        $typeService = $global ? 'setAsShared' : 'set';
       
        $this->container->$typeService(
            $this->serviceId,
            function() use ($component, $abc) {
                if (class_exists($component)) {
                    return new $component($abc);
                } else {
                    AbcError::badFunctionCall('<strong>'. $this->serviceId .'</strong>' . ABC_NOT_FOUND_SERVICE);
                }
            }
        );
    }  
    
   
}
