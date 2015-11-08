<?php

namespace ABC\abc\core\debugger;

/** 
 * Класс ExceptionHandler
 * Обработчик исключений 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */   
abstract class Handler
{
    public $spacePrefix = 'ABC'; 
    public $allTrace = false; 
    
    protected $exception = true;

    /**
    * $var string
    */      
    protected $message; 
    
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
    
    protected $E_Lavel = [
                E_WARNING,
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
        
        if (isset($config['error_language'])) {
            $this->language = $config['error_language'];
        }
        
        set_exception_handler([$this, 'exceptionHandler']);
        set_error_handler([$this, 'triggerErrorHandler']);
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
        $this->message   = $e->getMessage();
        $this->code      = $e->getCode();        
        $trace = $e->getTrace();
        $this->backTrace = $this->prepareTrace($trace);
        $this->createReport();  
    }
    
    /**
    * Отлавливает trigger_error
    *
    * @return void
    */   
    public function triggerErrorHandler($code, $message, $file, $line) 
    {  
        if (error_reporting() & $code) {
            $this->exception = false;
         
            if (!empty($this->language)) {
                $lang = '\ABC\Abc\Core\Debugger\Php\Lang\\'. $this->language;
                $this->message = $lang::translate($message);            
            } else {
                $this->message = $message;
            }
            
            $this->code      = $code; 
            $this->file      = $file;
            $this->line      = $line; 
            $trace = debug_backtrace();
         
            if (in_array($code, $this->E_Lavel)) {
                array_shift($trace);            
            }
         
            $trace = $this->prepareTrace($trace);
            $this->backTrace = array_reverse($trace);
            $this->createReport();
            die;            
        } 
        

    }
    
    /**
    * Обработчик исключений
    *
    * @return void
    */   
    public function createReport() 
    { 
        $this->data = ['message'  => $this->message,
                       'adds'     => isset($this->line),
                       'level'    => $this->lewelMessage($this->code),
                       'listing'  => $this->getListing(),                       
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
    * Подготавливает трассировку для исключений
    *
    * @return void
    */   
    protected function prepareTrace($trace)
    {      
        $j = 0;
        $blocks = [];
        foreach ($trace as $block) {
         
        if (empty($block['class'])) {
            $block['class'] = 'PHP';
            $block['type']  = '>>>';
            $this->file = $block['file'];
            $this->line = $block['line'];
        }


            $beforeClass = @$trace[$j + 1]['class'];   
            $j++;
            $block = $this->blocksFilter($block, $beforeClass);
            
            if (empty($block)) {
                continue;
            }
            
            $blocks[] = $block;
        }
        
        return $blocks;
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
        if ($block['function'] === 'triggerErrorHandler') {
            $block['file'] = $this->file;
            $block['line'] = $this->line;        
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
     
        if (!empty($block['args'][1]) && is_int($block['args'][1]) && in_array($block['args'][1], $this->E_Lavel)) {
            return false;
        }
        
        if ($block['function'] === 'trigger_error') {
            return false;
        } 
     
        if (false !== strpos($block['function'], '{closure}')) {
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