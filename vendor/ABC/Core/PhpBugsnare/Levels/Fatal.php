<?php

namespace ABC\Abc\Core\PhpBugsnare\Levels;

use ABC\Abc\Core\PhpBugsnare\Bugsnare;
use ABC\Abc\Core\PhpBugsnare\Handler;
/** 
 * Class Fatal
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author Nikolay Twin
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 * 
 */   
class Fatal extends Handler
{
    
    public function __construct($config) 
    {
        parent::__construct($config); 
    }

    /**
    * Catch fatal error
    *
    * @return void
    */   
    public function errorHandler() 
    {
        $report = Bugsnare::getReport();
         
        if (!empty($report)) {
            ob_end_clean();
            echo $report;
        }
     
        if ($error = error_get_last() AND $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
         
            ob_end_clean();
            $this->message = $error['message'];
            $this->code = $error['type']; 
            $this->file = $error['file'];
            $this->line = $error['line'];
            $this->createReport();
            
        } else {
            @ob_flush();
        }
    } 

    /**
    * Prepares report
    *
    * @return void
    */   
    protected function createReport() 
    {
        $block = ['file' => $this->file,
                  'line' => $this->line,
                  ];
     
        if (class_exists($this->language)) {
            $lang = $this->language;
            $this->message = $lang::translate($this->message);
        } 
        
        $this->data = ['message'  => $this->message,
                       'adds'     => true,
                       'level'    => $this->lewelMessage($this->code),
                       'listing'  => $this->prepareBlock($block),                       
                       'file'     => $this->file,
                       'line'     => $this->line,                       
                       'stack'    => '',
        ];
     
        $this->action();
    }
}
