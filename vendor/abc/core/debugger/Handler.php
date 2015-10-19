<?php

namespace ABC\abc\core\debugger;


/** 
 * Класс ExceptionHandler
 * Обработчик исключений 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */   

abstract class Handler
{
/**
 * @var string 
 */
    public $user = 'ABC'; 
/**
 * @var string 
 */
    public $framework = 'AbcProcessor';

/**
 * @var bool 
 */
    public $developer = false;

    
    
    protected $exception = false;
    protected $file;
    protected $line;
    protected $trace;
    protected $code;
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
    abstract protected function createStack();
    abstract protected function getListing();
    abstract protected function getStack();
    abstract protected function action();
    
 /**
 * Отлавливает исключения
 *
 * @return void
 */   
    public function exceptionHandler($e) 
    {
        $this->exception = true;
        $this->createReport($e);  
    }
    
 /**
 * Обработчик исключений
 *
 * @return void
 */   
    public function createReport($e) 
    {
        $this->code = $e->getCode();        
        $this->backTrace = $e->getTrace();
        $this->prepareTrace();
     
        $this->data = ['message'  => $e->getMessage(),
                       'adds'     => true,
                       'level'    => $this->lewelMessage($this->code),
                       'listing'  => $this->getListing($this->trace[0]),                       
                       'file'     => $this->file,
                       'line'     => $this->line,                       
                       'stack'    => $this->getStack(),
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
 * Подготавливает трассировку для генерации листингов
 *
 * @return void
 */   
    protected function prepareTrace()
    {    
        $blocks = [];
     
        foreach ($this->backTrace as $block) { 
            $block = $this->normaliseBlock($block);
         
            if (empty($block)) {
                continue;
            }  
         
            $blocks[] = $block;
        }
        
        $this->backTrace = $blocks; 
    }   

 /**
 * Приводит блоки трассировки к одному типу
 *
 * @param string $block
 *
 * @return string
 */    
    protected function normaliseBlock($block)
    { //
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
        if ($this->developer) {
            return $block;
        }
      
        $e_User = [
            E_USER_NOTICE,
            E_USER_WARNING,
            E_USER_ERROR
        ];
       
        switch ($block) {
         
            case ($this->checkFramework($block)) :
                return false;
                
            case (!empty($block['args'][1]) && is_int($block['args'][1]) && in_array($block['args'][1], $e_User)) :
                return false;
                
            case ($block['function'] === 'trigger_error') :
                return false;
           
            case (!empty($block['file'][1]) && false !== strpos($block['file'], 'eval')) :
                return false;
         
            default :
                return $block;
        } 
    } 
    
 /**
 * Распознает классы фреймворка
 *
 * @param array $block
 *var_dump($class);
 * @return bool
 */    
    protected function checkFramework($block)
    { 
        if (empty($block['class'])) {
            return false;
        }
    
        if (basename($block['class']) === $this->framework && (!$this->exception || $block['function'] === 'getComponent')) {
            return true;
        }
        
        if (basename($block['class']) === 'Dbg') {
            return true;
        }
        
        $user = preg_quote($this->user);        
        return preg_match('#^'. $user .'\\\\'. $user .'.+#i', $block['class']);
    }     
}
















