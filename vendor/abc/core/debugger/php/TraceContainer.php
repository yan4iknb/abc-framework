<?php

namespace ABC\Abc\Core\Debugger\Php;

/** 
 * Класс TrasseContainer
 * Трассировка контейнера.
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015 
 * @license http://abc-framework.com/license/ 
 */   

class TraceContainer
{
    public $message = 'Tracing Container ';
    public $adds = true;    
    public $container = 'ABC\abc\core\Container';
    
    protected $painter;
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
    public function getListing() 
    {
        $blockCont = $this->reflectionContainer($this->container);
        $data['total'] = $this->painter->highlightContainer($blockCont);        
        $data['lines'] = $this->createLine($blockCont);
        return $this->view->createListingContainer($data);
    }
  
    /**
    * Возвращает структуру объекта
    *
    * @param string $var
    *
    * @return string
    */ 
    protected function reflectionContainer($var) 
    { 
        return null;
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
