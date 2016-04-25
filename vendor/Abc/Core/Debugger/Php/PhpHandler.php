<?php

namespace ABC\Abc\Core\Debugger\Php;

use ABC\Abc\Core\Debugger\Handler;
use ABC\Abc\Core\Debugger\Php\View;
use ABC\Abc\Core\Debugger\Php\Painter;

/** 
 * Класс PhpHandler
 * Визуализирует отчет о пойманых исключениях.
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 * 
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
    * $param $blockCont
    */       
    public function __construct($abc) 
    {
        parent::__construct($abc);
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
        if ($this->exception) {
            $block = array_shift($this->backTrace);   
        } else {
            $block = array_pop($this->backTrace);
        }
        
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
    * @param string $blockCont
    *
    * @return string
    */     
    protected function prepareValue($blockCont) 
    {
        if ($blockCont === null) {
            $blockCont = 'Void';  
        } else {
            ob_start();
                var_dump($blockCont);       
            $blockCont = ob_get_clean();
        }
        return $blockCont;
    } 
    
    /**
    * Генерирует листинг участка кода
    *
    * @param array $block
    * @param int $num
    *
    * @return string
    */   
    protected function prepareBlock($block, $num = false) 
    { 
        $i = 0;
        $blockCont = ''; 
       
        $line = !empty($block['line']) ? $block['line'] : null;
        
        if (!empty($block['file'])) {
            $this->file  = $block['file'];
            $this->line  = $block['line'];
            $script = file($block['file']);        
        } else {
            return null;
        }
        
        $arguments = $this->prepareValue(@$block['args']);
        
        $ext = ceil($this->sizeListing / 2);
        $position = ($line <= $ext) ? 0 : $line - $ext;
        
        foreach ($script as $string) {
            ++$i;
         
            if($this->mainBlock && $i == $line) {
                $lines[] = $this->painter->wrapLine($i, 'error');
            } elseif($i == $line) {
                $lines[] = $this->painter->wrapLine($i, 'trace');
            }
            else {
                $lines[] = $i;
            }
            
            $blockCont .= $string;
        } 
       
        $lines = array_slice($lines, $position, $this->sizeListing);
        
        if (!$this->exception && $num === false) {
            $arguments = 'null';
        }
        $ext = pathinfo($this->file)['extension']; 
        
        if ($ext === 'tpl') {
            $total = $this->painter->highlightStringTpl($blockCont, $position, $this->sizeListing);
        } else {
            $total = $this->painter->highlightString($blockCont, $position, $this->sizeListing);
        }
        
        $data = ['num'       => $num,
                 'arguments' => $this->painter->highlightVar($arguments),
                 'lines'     => [$lines],
                 'total'     => $total,
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
        $i = $j = 0;
        $tpl    = $this->view->getStackRow();
        $action = '';
        $stack  = $rows = [];
        
        foreach ($this->backTrace as $block) {
         
            $class  = str_replace('\\', ABC_DS, $block['class']);
            $space  = str_replace(ABC_DS, '\\', dirname($class));
            $space  = str_replace('.', '\\', $space);
            $location = basename($this->file);

            $data = ['space'     => $space,
                     'location'  => $location,
                     'file'      => @$block['file'] ?: 'PHP',
                     'line'      => @$block['line'] ?: ' - ',
                     'total'     => $this->prepareBlock($block, $i)
            ];            
          
            $action = basename($class). $block['type'];
            $data['action'] = $action . $block['function'];
            
            $stack[] = $data;
            $i++;
        } 
        
        if ($this->exception) {
            $stack = array_reverse($stack);        
        }
     
        foreach ($stack as $row) {
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
        $this->abc->debugReport = $this->view->getReport($this->data);
    }
}