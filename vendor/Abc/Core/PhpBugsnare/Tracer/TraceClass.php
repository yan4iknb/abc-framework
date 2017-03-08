<?php

namespace ABC\Abc\Core\PhpBugsnare\Tracer;

/** 
 * Class TraceClass
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author Nikolay Twin
 * @copyright © 2015 
 * @license http://www.wtfpl.net/
 */   

class TraceClass
{
    public $message = ' Tracing Class<br />';
    public $adds = false;

    protected $painter;   
    protected $view;
    
    /**
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
        $blockCont = $this->reflectionClass($var);        
        $data['lines'] = $this->createLine($blockCont);    
        $data['total'] = $this->painter->highlightClass($blockCont); 
        return $this->view->createListingClass($data);
    } 
    
    /**
    * Returns the class structure
    *
    * @param string $var
    *
    * @return string
    */ 
    protected function reflectionClass($var) 
    { 
        $classInfo = new \ReflectionClass($var);
        return \Reflection::export($classInfo, true);
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
        $amountAnn = [];
        preg_match_all('#(/\*\*.+?\*/)#is', $blockCont, $annotations);
      
        foreach ($annotations[0] as $a) {
            $annotCnt = substr_count($a, "\n");
            $amountAnn[] = $annotCnt;
        }
     
        $linesCnt = range(1, substr_count((string)$blockCont, "\n") + 4);        
        return [$linesCnt, $amountAnn];
    }
}

