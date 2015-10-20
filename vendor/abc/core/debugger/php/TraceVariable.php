<?php

namespace ABC\abc\core\debugger\php;

/** 
 * Класс TraceVariable
 * Трассировка переменной.
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015 
 * @license http://abc-framework.com/license/ 
 */   

class TraceVariable
{
    public $message = 'Tracing Variable ';
    public $adds = true;    
  
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
        $data['lines'] = $this->createLine($var);        
        $data['total'] = $this->painter->highlightVar($var);
        return $this->view->createListingVariable($data);
    } 

    /**
    * Генерирует столбик нумерации линий
    *
    * @return string
    */     
    protected function createLine($blockCont) 
    {   
        $linesCnt = range(1, substr_count((string)$blockCont, "\n") + 3);
        return [$linesCnt];
    }  
}

