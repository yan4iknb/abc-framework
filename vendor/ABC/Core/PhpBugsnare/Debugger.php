<?php
    
namespace ABC\ABC\Core\PhpBugsnare;

use ABC\ABC\Core\PhpBugsnare\Handler;
use ABC\ABC\Core\PhpBugsnare\Tracer\TraceClass;
use ABC\ABC\Core\PhpBugsnare\Tracer\TraceObject;
use ABC\ABC\Core\PhpBugsnare\Tracer\TraceVariable;

/** 
 * Class Dbg
 * Tracing the script.
 * NOTE: Requires PHP version 5.5 or later   
 * @author Nikolay Twin
 * @copyright © 2015 
 * @license http://www.wtfpl.net/  
 */   
class Debugger extends Handler
{

    protected $tracer;
    protected $trace = true;
    protected $reflection = false;
    protected $errorLevel = E_USER_ERROR;
 
    public function __construct($var = 'no arguments', $config)
    {
        parent::__construct($config);
        $this->tracersSelector($var);
    }

    /**
    * Choosing a tracers, depending on the type of data
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
        } elseif (is_object($var)) {
            $this->tracer = new TraceObject($this->painter, $this->view);               
        } else {
            $this->tracer = new TraceVariable($this->painter, $this->view);
        }
        
        $this->traceProcessor($var);
    }     
 
    /**
    * Starts tracing
    *
    * @param mixed $var
    *
    * @return void
    */      
    protected function traceProcessor($var) 
    {
        $trace = debug_backtrace();
        $this->backTrace = $this->prepareTrace($trace); 
        
        if (!$this->reflection) {
            $var = $this->prepareValue($var);        
        } 
        
        $location = $this->getLocation();
        $listing  = $this->tracer->getListing($var);
        $this->create($location, $listing);
    } 

    /**
    * Returns the file and the trace line
    *
    * @return string
    */        
    protected function getLocation() 
    { 
        $blocs = [];
     
        foreach ($this->backTrace as $block) {
            $block = $this->normalizeBlock($block);    
            
            if (empty($block)) {
                continue;
            }
            
            $blocs[] = $block;
        }
     
        return $blocs[0];
    }
    
    /**
    * Generates a report
    *
    * @param array $location
    * @param string $listing
    *
    * @return void
    */    
    protected function create($location, $listing) 
    { 
        $this->data = ['message'  => $this->tracer->message,
                       'adds'     => $this->tracer->adds,
                       'level'    => $this->lewelMessage($this->errorLevel),
                       'listing'  => $listing,                       
                       'file'     => $location['file'],
                       'line'     => $location['line'],                       
                       'stack'    => $this->getStack(),
        ];
        
        $this->exception = false;
        $this->action();
    }  
}
