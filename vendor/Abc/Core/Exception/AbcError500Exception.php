<?php

namespace ABC\Abc\Core\Exception;

/** 
 * Класс DebugException 
 * Адаптирует trigger_error к Exception
 * для корректного выброса исключения
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */  

class AbcError500Exception extends \Exception 
{
    /**
    * Генерирует сзаголовок Error 500 Internal Server Error
    * на сообщения об ошибках  
    *
    * @param string $message
    * @param string $code
    * @param string $file 
    * @param string $line 
    *
    * @return void
    */     
    public function __construct($message, $code = null, $file = null, $line = null) 
    {
        header("HTTP/1.1 500 Internal Server Error");
        $page = <<<EOD
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="origin" name="referrer">
    <title>Internal Server Error</title>
   </head>
   <body>
        <div style="text-align:center">
            <img src="data:image/png;base64,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" border="0" />
        </div>
    </body>
</html>
EOD;
        exit($page);
    }
}  