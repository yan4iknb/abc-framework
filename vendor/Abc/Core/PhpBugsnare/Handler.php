<?php

namespace ABC\Abc\Core\PhpBugsnare;

/** 
 * Class Handler
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author Nikolay Twin
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 * 
 */   
class Handler
{
    public $spacePrefix = 'ABC'; 
    public $allTrace = false; 
    public $sizeListing = 20;

    protected $exception = true;
    protected $message;    
    protected $file;  
    protected $line;
    protected $trace;
    protected $code;
    protected $data;
    protected $view;
    protected $painter;
    protected $allReports = [];
    protected $num = 0;
    protected $mainBlock = true;
    protected $E_Lavel = [
                E_STRICT,
                E_USER_NOTICE,
                E_USER_WARNING,
                E_USER_ERROR
            ];
   
    public function __construct($config) 
    {
        $this->language = $config['language'];
        $this->language = '\ABC\Abc\Core\PhpBugsnare\Lang\\'. $this->language;
     
        if (isset($config['framework_trace']) && true === $config['framework_trace']) {
            $this->allTrace = true;
        }
      
        $this->painter  = new Painter;
        $this->view     = new View;
    }
    
    /**
    * Prepare a message about the type of error
    *
    * @return string
    */       
    protected function lewelMessage($level) 
    {
        $listLevels = [
                        E_NOTICE        => 'PHP Notice: ',
                        E_WARNING       => 'PHP Warning: ',
                        E_STRICT        => 'PHP Strict: ',
                        E_ERROR         => 'PHP Fatal error: ',
                        E_COMPILE_ERROR => 'PHP Compile error: ',
                        E_CORE_ERROR    => 'PHP Code error: ',
                        E_PARSE         => 'PHP Parse error: ',
                        E_USER_NOTICE   => 'User Notice: ',
                        E_USER_WARNING  => 'User Warning: ',
                        E_USER_ERROR    => 'PhpBugsnare Message: '                        
        ];
        
        return !empty($listLevels[$level]) ? $listLevels[$level] : 'PhpBugsnare debug mode: ';
    } 
 
    /**
    * Prepares trace for the exceptions
    *
    * @param array $trace
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
                $this->file = @$block['file'];
                $this->line = @$block['line'];
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
    * Filters trace
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
        
        if (basename($block['class']) === 'Debugger') {
            return false;
        } 
        
        return $block;
    }
    
    /**
    * Prepares report
    *
    * @return void
    */   
    protected function createReport() 
    {
        if (class_exists($this->language)) {
            $lang = $this->language;
            $this->message = $lang::translate($this->message);
        } 
        
        $this->data = ['message'  => $this->message,
                       'adds'     => isset($this->line),
                       'level'    => $this->lewelMessage($this->code),
                       'listing'  => $this->getListing(),                       
                       'file'     => $this->file,
                       'line'     => $this->line,                       
                       'stack'    => $this->getStack(),
        ];
        
        $this->mainBlock = true;
        $this->action();
    }    
    
    /**
    * Returns the main piece of code block
    *
    * @return string
    */   
    protected function getListing() 
    {
        if ($this->exception) {
            $block = array_shift($this->backTrace);   
        } else {
            $block = array_pop($this->backTrace);
        }
        
        return $this->prepareBlock($block);
    }
    
    /**
    * Generates code section listing
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
            } elseif ($i == $line) {
                $lines[] = $this->painter->wrapLine($i, 'trace');
            } else {
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
        
        if (!$this->exception && $num === false) {
            $arguments = 'null';
        }
     
        $data = ['num'       => $num,
                 'arguments' => $this->painter->highlightVar($arguments),
                 'lines'     => [$lines],
                 'total'     => $total,
        ];
      
        return $this->view->createBlock($data);
    } 
    
    /**
    * Normalizes blocks
    *
    * @param string $block
    *
    * @return string
    */    
    protected function normalizeBlock($block)
    {
        if ($block['function'] === 'triggerErrorHandler') {
            $block['file'] = $this->file;
            $block['line'] = $this->line;        
        }
        
        return $this->blocksFilter($block);
    } 
    
    /**
    * Returns the trace
    *
    * @return string
    */    
    public function getStack() 
    { 
        $this->mainBlock = false;    
        return $this->prepareStack(); 
    } 
    
    /**
    * Generates a trace table
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
         
            $class  = str_replace('\\', DIRECTORY_SEPARATOR, $block['class']);
            $space  = str_replace(DIRECTORY_SEPARATOR, '\\', dirname($class));
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
            usleep(100);
            $row['uniq']  = $uniq = md5(microtime(true));
            $rows[] = $this->view->parseTpl($tpl, $row);
        }
        
        $data = ['cnt'  => $this->num,
                 'rows' => implode('', $rows)
        ];
        
        return $this->view->createStack($data);
    } 

    /**
    * Preparing data for listings
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
        
        return preg_match('#^'. preg_quote($this->spacePrefix) .'\\\abc.*#iu', $beforeClass);
    }
    
    /**
    * Action
    *
    * @return void
    */   
    public function action() 
    {       
        $this->data['num']  = $this->num;
        $debugReport = $this->view->getReport($this->data);
     
        if ($this->exception) {
            ob_end_clean();
            print($debugReport);
        } else {
            Hoarder::add($debugReport);        
        }
    }
}
