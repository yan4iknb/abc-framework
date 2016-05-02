<?php

namespace ABC\Abc\Components\Debugger\Syntax;

use ABC\Abc\Components\Debugger\Painter;
use ABC\Abc\Components\Debugger\View;
use ABC\Abc\Components\Debugger\Handler;

/** 
 * Класс PhpHandler
 * Визуализирует отчет о пойманых исключениях.
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 * 
 */   
class SyntaxHandler
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

    
    /**
    * Конструктор
    *
    * $param $blockCont
    */       
    public function __construct($config) 
    {
        $this->config = $config;
        $this->view    = new View;
        $this->painter = new Painter;
    }
    
    /**
    * Отлавливает trigger_error
    *
    * @return void
    */   
    public function triggerErrorHandler($code, $message, $file, $line) 
    {
        if (error_reporting() & $code) {
         
            if (!empty($this->config['error_language'])) {
                $lang = '\ABC\Abc\Components\Debugger\Lang\\'. $this->config['error_language'];
                $this->message = $lang::translate($message);            
            } else {
                $this->message = $message;
            }
        }
        
        $this->file = $file;
        $this->line = $line;
        $this->createReport();
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
    protected function getListing() 
    { 
        $i = 0;
        $blockCont = ''; 
       
        $script = file($this->file);        


        $ext = ceil($this->sizeListing / 2);
        $position = ($this->line <= $ext) ? 0 : $this->line - $ext;
        
        foreach ($script as $string) {
            ++$i;
         
            if($i === $this->line) {
                $lines[] = $this->painter->wrapLine($i, 'error');
            }
            else {
                $lines[] = $i;
            }
            
            $blockCont .= $string;
        } 
       
        $lines = array_slice($lines, $position, $this->sizeListing);  
        $ext = pathinfo($this->file)['extension']; 
        
        if ($ext === 'tpl') {
            $total = $this->painter->highlightStringTpl($blockCont, $position, $this->sizeListing);
        } else {
            $total = $this->painter->highlightString($blockCont, $position, $this->sizeListing);
        }
        
        $data = [
                 'num'       => null,
                 'arguments' => null,
                 'lines'     => [$lines],
                 'total'     => $total,
        ];
      
        return $this->view->createBlock($data);
    }   
    
    /**
    * Подготавливает отчет
    *
    * @return void
    */   
    public function createReport() 
    { 
        $this->data = ['message'  => $this->message,
                       'adds'     => isset($this->line),
                       'level'    => 'Parse error: ',
                       'listing'  => $this->getListing(),                       
                       'file'     => $this->file,
                       'line'     => $this->line,
                       'stack'    => null
        ];
               
        $this->data['num']  = $this->num;
        $this->view->displayReport($this->data);
    }
}