<?php

namespace ABC\abc\core\debugger;

use ABC\abc\core\debugger\php\PhpHandler;
use ABC\abc\core\debugger\php\TraceClass;
use ABC\abc\core\debugger\php\TraceObject;
use ABC\abc\core\debugger\php\TraceContainer;
use ABC\abc\core\debugger\php\TraceVariable;

/** 
 * Класс Dbg
 * Трассировка скрипта.
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015 
 * @license http://abc-framework.com/license/  
 */   

class Dbg extends PhpHandler
{

    public $container = 'ABC\abc\core\Container';
    
    /**
    * @var object 
    */    
    protected $tracer;
    
    protected $trace = true;
    protected $reflection = false;
    protected $errorLevel = E_USER_ERROR;

    /**
    * Конструктор
    *
    * @param mixed $var
    * @param mixed $no
    */    
    public function __construct($var = 'stop', $no = null)
    {
        if ($no !== null) {
            trigger_error('Function expects exactly one parameter.', E_USER_WARNING);
        }
     
        parent::__construct();
        $this->tracersSelector($var);
        $this->traceProcessor($var);
    }

    /**
    * Выбор трассировщика в зависимости от типа данных
    *
    * @param mixed $var
    *
    * @return void
    */     
    protected function tracersSelector($var) 
    { 
        if (is_string($var) && class_exists($var)) {
            $this->tracer = new TraceClass($this->painter, $this->view);
            $this->reflection = true;
            
        } elseif(is_object($var)) {
         
            if (get_class($var) === $this->container) {
                $this->tracer = new TraceContainer($this->painter, $this->view);
                $this->reflection = true;
            } 
            else {
                $this->tracer = new TraceObject($this->painter, $this->view);
            }  
        } 
        else {
            $this->tracer = new TraceVariable($this->painter, $this->view);
        }
    }     
 
    /**
    * Запускает трассировку
    *
    * @param mixed $var
    *
    * @return void
    */      
    protected function traceProcessor($var) 
    {
        $this->backTrace = debug_backtrace();
        $this->prepareTrace(); 
        
        if (!$this->reflection) {
            $var = $this->prepareValue($var);        
        } 
        
        $location = $this->getLocation();
        $listing  = $this->tracer->getListing($var);
        $this->render($location, $listing);
    } 

    /**
    * Возвращает файл и линию трассировки
    *
    * @return void
    */        
    protected function getLocation() 
    { 
        $blocs = [];
        
        foreach ($this->backTrace as $block) {
            $block = $this->normaliseBlock($block);    
            
            if (empty($block)) {
                continue;
            }
            
            $blocs[] = $block;
        }
     
        return $blocs[0];
    }
    
    /**
    * Рендер 
    *
    * @param array $location
    * @param string $listing
    *
    * @return void
    */    
    protected function render($location, $listing) 
    { 
        $this->data = ['message'  => $this->tracer->message,
                       'adds'     => $this->tracer->adds,
                       'level'    => $this->lewelMessage($this->errorLevel),
                       'listing'  => $listing,                       
                       'file'     => $location['file'],
                       'line'     => $location['line'],                       
                       'stack'    => $this->getStack(),
        ];
        
        $this->action();
        die;
    }  
}
