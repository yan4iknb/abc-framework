<?php

namespace ABC\Abc\Services;

use ABC\Abc;
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
    protected $normalize = [
        'bbdecoder'  => Abc::BBDECODER,
        'container'  => Abc::CONTAINER,
        'paginator'  => Abc::PAGINATOR,
        'psr7'       => Abc::PSR7,
        'params'     => Abc::PARAMS,
        'dbcommand'  => Abc::DBCOMMAND,
        'mysqli'     => Abc::MYSQLI,
        'pdo'        => Abc::PDO,
        'sqldebug'   => Abc::SQLDEBUG,
        'storage'    => Abc::STORAGE,
        'template'   => Abc::TEMPLATE,
        'tplnative'  => Abc::TPLNATIVE,
        'urimanager' => Abc::URIMANAGER,
    ];
    protected $subDir = [
        'DbCommand'     => 'Sql',
        'Mysqli'        => 'Sql',
        'Pdo'           => 'Sql',
        'SqlDebug'      => 'Sql',
        'Template'      => 'Tpl',
        'TplNative'     => 'Tpl',
    ];
    
    public function __construct($serviceId, $abc)
    {
        if (!isset($this->normalize[strtolower($serviceId)])) {
            throw new \badFunctionCallException('<strong>'
                                     . $serviceId 
                                     .'</strong>' 
                                     . ABC_NOT_FOUND_SERVICE
            );
        } else {  
            $this->serviceId = $this->normalize[strtolower($serviceId)];
            $this->abc = $abc;
            $this->container = $abc->getContainer();
            $this->dir = !empty($this->subDir[$serviceId]) ? $this->subDir[$serviceId] .'\\' : null;
        }
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
            strtolower($this->serviceId),
            function() use ($component, $abc) {
                return new $component($abc);
            });
    }  
}
