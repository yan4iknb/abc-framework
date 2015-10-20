<?php

namespace ABC\abc\core\debugger\php;

use ABC\abc\core\debugger\Handler;
use ABC\abc\core\debugger\php\View;
use ABC\abc\core\debugger\php\Painter;

/** 
 * Класс PhpHandler
 * Визуализирует отчет о пойманых исключениях.
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 * @package system.cmponents.debugger 
 */   

class PhpHandler extends Handler
{

    public $sizeListing = 20;
    
    /**
    * @var View 
    */    
    protected $view;
    
    /**
    * @var Painter 
    */    
    protected $painter;
    
    protected $num = 0;
    protected $mainBlock = true;
    
    /**
    * Конструктор
    *
    */       
    public function __construct() 
    {
        parent::__construct();
        $this->view    = new View;
        $this->painter = new Painter;
    }
    
    /**
    * Возвращает главный блок участка кода
    *
    * @return string
    */   
    public function getListing() 
    {
        $block = array_shift($this->backTrace);
        return $this->prepareBlock($block);
    }

    /**
    * Возвращает листинги трассировки
    *
    * @return string
    */    
    public function getStack() 
    { 
        $this->mainBlock = false;    
        return $this->prepareStack(); 
    }  

    /**
    * Подготовка данных для листингов
    *
    * $param $blockCont
    *
    * @return void
    */     
    protected function prepareValue($blockCont) 
    {
        if (empty($blockCont)) {
            $blockCont = 'Void';  
        } 
     
        ob_start();
            var_dump($blockCont);       
        $blockCont = ob_get_clean();
     
        return $blockCont;
    } 
    
    /**
    * Генерирует листинг участка кода
    *
    * @return string
    */   
    protected function prepareBlock($block, $num = false) 
    { 
        $i = 0;
        $blockCont = ''; 
      
        $this->line  = $block['line'];        
        $this->file  = $block['file'];
        $script = file($block['file']);
        $arguments = $this->prepareValue(@$block['args']);
        
        $ext = ceil($this->sizeListing / 2);
        $position = ($this->line <= $ext) ? 0 : $this->line - $ext;
        
        foreach ($script as $string) {
            ++$i;
         
            if($this->mainBlock && $i == $this->line) {
                $lines[] = $this->painter->wrapLine($i, 'error');
            } elseif($i == $this->line) {
                $lines[] = $this->painter->wrapLine($i, 'trace');
            }
            else {
                $lines[] = $i;
            }
            
            $blockCont .= $string;
        } 
       
        $lines = array_slice($lines, $position, $this->sizeListing);
        
        if ($this->exception && $num === false) {
            $arguments = 'null';
        }
        
        $data = ['num'       => $num,
                 'arguments' => $this->painter->highlightVar($arguments),
                 'lines'     => [$lines],
                 'total'     => $this->painter->highlightString($blockCont, $position, $this->sizeListing),
        ];
      
        return $this->view->createBlock($data);
    }     

    /**
    * Генерирует таблицу трассировки
    *
    * @return string
    */   
    protected function prepareStack()
    {    
        $i = 0;
        $tpl    = $this->view->getStackRow();
        $action = '';
        $steck  = $rows = [];
       
        foreach ($this->backTrace as $block) {
            
            $block = $this->normaliseBlock($block);
         
            if (empty($block)) {
                continue;
            }  
            
            $class  = $block['class'] ?: 'GLOBALS';
            $class  = str_replace('\\', DIRECTORY_SEPARATOR, $block['class']);
            $space  = str_replace(DIRECTORY_SEPARATOR, '\\', dirname($class));
            $location = basename($this->file);
            
            $data = ['space'     => $space,
                     'location'  => $location,
                     'file'      => $block['file'],
                     'line'      => $block['line'],
                     'total'     => $this->prepareBlock($block, $i)
            ];            
         
            if (!empty($block['class'])) { 
                $action = basename($class). $block['type']; 
            } 
            
            $data['action'] = $action . $block['function'];
            
            $steck[] = $data;
            $i++;
        }
        
        $steck = array_reverse($steck);    
        
        foreach ($steck as $row) {
            $row['num'] = ++$this->num;        
            $rows[] = $this->view->parseTpl($tpl, $row);
        }
        
        $data = ['cnt'  => $this->num,
                 'rows' => implode('', $rows)
        ];
        
        return $this->view->createStack($data);
    }    

    /**
    * Рендер
    *
    * @return void
    */   
    public function action() 
    {
        $this->data['num']  = $this->num;
        $this->view->displayReport($this->data);
    }
}


