<?php

namespace ABC\Abc\Core\PhpBugsnare\Tracer;
 
/** 
 * Class TraceVariable
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author Nikolay Twin
 * @copyright © 2015 
 * @license http://www.wtfpl.net/
 */   
class TraceVariable
{
    public $message = ' Tracing <br />';
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
        $data['lines'] = $this->createLine($var);        
        $data['total'] = $this->painter->highlightVar($var, false);
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
