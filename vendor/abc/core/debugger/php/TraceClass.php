<?php

namespace ABC\abc\core\debugger\php;

/** 
 * Класс TraceClass
 * Содержимое класса.
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015 
 * @license http://abc-framework.com/license/ 
 */   

class TraceClass
{
    public $message = 'Tracing Class ';
    public $adds = false;
    
    /**
    * @var object 
    */        
    protected $painter;
    
    /**
    * @var object 
    */    
    protected $view;
    
    /**
    * Конструктор
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
    * Возвращает сформированный листинг
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
    * Возвращает структуру класса
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
    * Генерирует массивы столбика нумерации линий
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

