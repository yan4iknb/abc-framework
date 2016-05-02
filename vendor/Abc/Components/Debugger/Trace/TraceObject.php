<?php

namespace ABC\Abc\Components\Debugger\Trace;

/** 
 * Класс TrasseObject
 * Трассировка объекта.
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015 
 * @license http://www.wtfpl.net/
 */   

class TraceObject
{
    public $message = ABC_TRACING_OBJECT;
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
        $data['total'] = $this->painter->highlightObject($var);
        $data['lines'] = $this->createLine($var);
        return $this->view->createListingVariable($data);
    }  
    
    /**
    * Генерирует массив столбика нумерации линий
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

