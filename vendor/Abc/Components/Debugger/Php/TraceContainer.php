<?php

namespace ABC\Abc\Components\Debugger\Php;

/** 
 * Класс TrasseContainer
 * Трассировка контейнера.
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015 
 * @license http://www.wtfpl.net/ 
 */   

class TraceContainer
{
    public $message = ABC_TRACING_CONTAINER;
    public $adds = true;    
    public $containerName;
    
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
    public function getValue() 
    {
        $var = $this->reflectionContainer();
        ob_start();
            var_dump($var);
        $this->value = ob_get_clean();
        return $this->value;
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
        $data['total'] = $this->painter->highlightContainer($this->value);        
        $data['lines'] = $this->createLine($this->value);
        return $this->view->createListingContainer($data);
    }
  
    /**
    * Возвращает структуру объекта
    *
    * @param string $var
    *
    * @return string
    */ 
    protected function reflectionContainer() 
    { // Не реализовано
        return new $this->containerName;
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
