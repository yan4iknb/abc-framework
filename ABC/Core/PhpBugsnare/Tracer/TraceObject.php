<?php

namespace ABC\ABC\Core\PhpBugsnare\Tracer;

/** 
 * Class TrasseObject
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author Nikolay Twin
 * @copyright © 2015 
 * @license http://www.wtfpl.net/
 */   

class TraceObject
{
    public $message = ' Tracing Object<br />';
    public $adds = true;

    protected $painter;  
    protected $view;
    
    /**
    *
    * @param object $painter
    * @param object $view 
    */   
    public function __construct($painter, $view) 
    { 
        $this->view = $view;
        $this->painter = $painter;
    }      
    
    /**
    * Returns generated listing
    *
    * @param string $var
    *
    * @return string
    */   
    public function getListing($var) 
    {
        $data['total'] = $this->painter->highlightObject($var);
        $data['lines'] = $this->createLine($var);
        return $this->view->createListingVariable($data);
    }  
    
    /**
    * Generate arrays column numbering lines
    *
    * @param string $blockCont
    *
    * @return array
    */     
    protected function createLine($blockCont) 
    { 
        $linesCnt = range(1, substr_count((string)$blockCont, "\n") + 3);
        return [$linesCnt];
    } 

}

