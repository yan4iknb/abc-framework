<?php

namespace ABC\abc\components\debuger;


/** 
 * Класс ExceptionHandler
 * Обработчик исключений 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 * @package system.cmponents.debugger 
 */   

abstract class ExceptionHandler
{
/**
 * @var string 
 */
    public $user = 'ABC'; 
    
/**
 * @var string 
 */
    protected $file;
/**
 * @var int 
 */
    protected $line;
/**
 * @var array 
 */
    protected $trace;
/**
 * @var int 
 */
    protected $code;
/**
 * @var array 
 */
    protected $data;

 /**
 * Конструктор
 *
 * @param string $message
 * @param int $errorLevel
 */       
    public function __construct() 
    {
        set_exception_handler(array($this, 'exceptionHandler'));
        
    }
    
 /**
 * Абстрактные методы. Реализация в потомках
 *
 * @param string $message
 * @param int $errorLevel
 */ 
    abstract protected function getLocation();
    abstract protected function getTrace();
    abstract protected function createCode(); 
    abstract protected function createTrace();
    abstract protected function action();
    
 /**
 * Обработчик исключений
 *
 * @return void
 */   
    public function exceptionHandler($e) 
    {
        $this->code  = $e->getCode();        
        $this->trace = $e->getTrace();
        $this->prepareTrace();
     
        $this->data = ['message'  => $e->getMessage(),
                       'level'    => $this->lewelMessage($e->getCode()),
                       'location' => $this->getLocation(),                       
                       'file'     => $this->file,
                       'line'     => $this->line,                       
                       'trace'    => $this->getTrace(),
        ];
        
        $this->action();
    }
 

 /**
 * Готовит сообщение о типе ошибки
 *
 * @return string
 */       
    protected function lewelMessage($level) 
    {
        $listLevels = [
                        E_NOTICE        => 'PHP Notice: ',
                        E_WARNING       => 'PHP Warning: ',
                        E_USER_NOTICE   => $this->user .' Notice: ',
                        E_USER_WARNING  => $this->user .' Warning: ',
                        E_USER_ERROR    => $this->user .' Message: '
        ];
        
        return !empty($listLevels[$level]) ? $listLevels[$level] : $this->user .' debug mode: ';
    }    

 /**
 * Подготвливает трассировку для генерации листингов
 *
 * @return void
 */   
    protected function prepareTrace()
    {    
        $blocks = [];
     
        foreach ($this->trace as $block) {
            
            $block = $this->normaliseBlock($block);
         
            if (empty($block)) {
                continue;
            }  
            
            $blocks[] = $block;
        }
        
        $this->trace = $blocks; 
    }   

 /**
 * Приводит блоки трассировки к одному типу
 *
 * @param string $code
 *
 * @return string
 */    
    protected function normaliseBlock($block)
    {  
        if ($block['function'] == 'setException') {
            $block = ['file'      => $block['args'][2],
                      'line'      => $block['args'][3],
                      'function'  => $block['function'],
                      'class'     => $block['class'],
                      'type'      => $block['type'],
                      'args'      => [0, $block['args'][0]]
            ];
        } 
        return $this->blocksFilter($block);
    }    

 /**
 * Фильтрует трассировку
 *
 * @param array $block
 *
 * @return array|bool
 */    
    protected function blocksFilter($block)
    {
        $e_User = [
            E_USER_NOTICE,
            E_USER_WARNING,
            E_USER_ERROR
        ];
     
        switch ($block) {
            case (empty($block)) :
                return false;
          
            case (!empty($block['args'][1]) && in_array($block['args'][1], $e_User)) :
                return false;
         
            case ($block['function'] === 'trigger_error') :
                return false;
           
            case (false !== strpos($block['file'], 'eval')) :
                return false;
         
            default :
                return $block;
        } 
    }   
   
}