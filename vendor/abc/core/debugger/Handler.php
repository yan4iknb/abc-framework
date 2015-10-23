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
    public $spacePrefix = 'ABC'; 
    public $allTrace = false; 
    
    protected $exception = false;
    
    /**
    * $var string
    */      
    protected $file;
    
    /**
    * $var int
    */  
    protected $line;
    
    /**
    * $var array
    */  
    protected $trace;
    
    /**
    * $var int
    */  
    protected $code;
    
    /**
    * $var array
    */  
    protected $data;
    
    protected $E_User = [
                E_USER_NOTICE,
                E_USER_WARNING,
                E_USER_ERROR
            ];
    /**
    * Конструктор
    *
    * @param string $message
    * @param int $errorLevel
    */       
    public function __construct($config = []) 
    {
        if (isset($config['framework_trace'])) {
            $this->allTrace = true;
        }
     
        if (isset($config['space_prexix'])) {
            $this->spacePrefix = $config['space_prexix'];
        }
        
        set_exception_handler(array($this, 'exceptionHandler'));   
    }
    
    /**
    * Абстрактные методы. Реализация в потомках
    *
    * @param string $message
    * @param int $errorLevel
    */  
    abstract protected function prepareStack();
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
        $this->exception = in_array($e->getCode(), $this->E_User);
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
                        E_USER_NOTICE   => 'ABC Notice: ',
                        E_USER_WARNING  => 'ABC Warning: ',
                        E_USER_ERROR    => 'ABC Message: '
        ];
        
        return !empty($listLevels[$level]) ? $listLevels[$level] : 'ABC debug mode: ';
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
    protected function blocksFilter($block, $beforeClass = '')
    { 
        if (!empty($block['file'][1]) && false !== strpos($block['file'], 'eval')) {
            return false;
        }
     
        if ($this->allTrace) {
            return $block;
        }
        
        if ($this->checkFramework($beforeClass)) {
            return false;
        }
     
        if (!empty($block['args'][1]) && is_int($block['args'][1]) && in_array($block['args'][1], $this->E_User)) {
            return false;
        }
        
        if ($block['function'] === 'trigger_error') {
            return false;
        } 

        return $block;
    } 
    
    /**
    * Распознает классы фреймворка
    *
    * @param array $block
    *
    * @return bool
    */    
    protected function checkFramework($beforeClass)
    {   
        if (empty($beforeClass)) {
            return false;
        }
     
        $spacePrefix = preg_quote($this->spacePrefix);        
        return preg_match('#^'. $spacePrefix .'\\\abc.*#iu', $beforeClass);
    }     
}













