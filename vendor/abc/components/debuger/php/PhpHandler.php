<?php

namespace ABC\abc\components\debuger\php;

use ABC\abc\components\debuger\ExceptionHandler as ExceptionHandler;
use ABC\abc\components\debuger\php\PhpTemplates as PhpTemplates;

/** 
 * Класс PhpHandler
 * Обработчик исключений 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 * @package system.cmponents.debugger 
 */   

class PhpHandler extends ExceptionHandler
{
/**
 * @var string
 * @access public
 */
    public $sizeListing = 20;
/**
 * @var object 
 */    
    protected $tpl; 
/**
 * @var int 
 */      
    protected $num = 1;
/**
 * @var bool
 */  
    protected $mainBlock = true;
    
 /**
 * Конструктор
 *
 * @param string $message
 * @param int $errorLevel
 */       
    public function __construct($message, $errorLevel) 
    {
        parent::__construct($message, $errorLevel);
        $this->tpl = new PhpTemplates;
    }
    
 /**
 * Возвращает главный блок участка кода
 *
 * @return string
 */   
    public function getLocation() 
    {
        $this->block = array_shift($this->trace); 
        return $this->createCode($this->block);
    }

 /**
 * Возвращает листинги трассировки
 *
 * @return string
 */    
    public function getTrace() 
    { 
        $this->mainBlock = false;    
        return $this->createTrace(); 
    }   
    
 /**
 * Генерирует листинг участка кода
 *
 * @return string
 */   
    protected function createCode() 
    { 
        $i = 0;
        $code = ''; 
        
        $this->line = $this->block['line'];        
        $this->file = $this->block['file'];

        $script = file($this->block['file']);      
        $arguments = var_export($this->block['args'], true);
        
        $ext = ceil($this->sizeListing / 2);
        $position = ($this->line <= $ext) ? 0 : $this->line - $ext;
        
        foreach ($script as $string) {
            ++$i;
         
            if($this->mainBlock && $i == $this->line) {
                $lines[] = $this->tpl->wrapLine($i, 'error');
            } elseif($i == $this->line) {
                $lines[] = $this->tpl->wrapLine($i, 'trace');
            }
            else {
                $lines[] = $i;
            }
            
            $code .= $string;
        } 
        
        $lines = array_slice($lines, $position, $this->sizeListing);
        
        $data = ['num'       => $this->num++,
                 'hide'      => $this->mainBlock,
                 'arguments' => $this->tpl->highlightVarExport($arguments),
                 'lines'     => implode("<br>", $lines),
                 'code'      => $this->tpl->highlightString($code, $position, $this->sizeListing),
        ];
      
        return $this->tpl->createBlock($data);
    }     

 /**
 * Генерирует таблицу трассировки
 *
 * @return string
 */   
    protected function createTrace()
    {    
        $i = 0;
        $action = '';
        $rows   = [];
        $tpl    = $this->tpl->getTraceRow();
       
        foreach ($this->trace as $this->block) {
            
            $this->block = $this->normaliseBlock($this->block);
         
            if (empty($this->block)) {
                continue;
            }  
            
            $data = ['num'       => $i,
                     'space'     => !empty($this->block['class']) ? $this->block['class'] : 'GLOBALS',
                     'location'  => ltrim(substr($this->file, strrpos($this->file, DIRECTORY_SEPARATOR)), '\\'),
                     'file'      => $this->block['file'],
                     'line'      => $this->block['line'],
                     'php'       => $this->createCode($this->block)
            ];            
         
            if (!empty($this->block['class'])) { 
                $action = substr($this->block['class'], strrpos($this->block['class'], DIRECTORY_SEPARATOR));
                $action = ltrim($action, '\\') . $this->block['type']; 
            } 
            
            $data['action'] = $action . $this->block['function'];
            $rows[] = $this->tpl->parseTpl($tpl, $data);
            
            $i++;
        }
        
        $rows = array_reverse($rows);
        $subdata = ['cnt'  => count($this->trace),
                    'rows' => implode('', $rows)
        ];
        
        return $this->tpl->createTrace($subdata);
    }    

 /**
 * Рендер
 *
 * @return void
 */   
    public function action() 
    {
        $this->tpl->displayReport($this->data);
    }   
}