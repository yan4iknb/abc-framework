<?php

namespace ABC\Abc\Components\Debugger\Sql;

use ABC\Abc\Components\Debugger\Sql\View;

/** 
 * Класс SqlDedug
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */  
class SqlDebug
{
   
    public $sizeListing = 30; 
    public $db; 
    public $component;    
    public $trace;
    
    /**
    * @var View
    */
    protected $view; 

    protected $explain;    
    protected $file;
    protected $line;
    protected $lang;
    protected $message = 'SQL error: ';    
    
    /**
    * Конструктор
    *
    * @param object $mysqli
    * @param object $view
    */        
    public function __construct($сonfig)
    { 
        $this->view = new View;
       
        if (!empty($сonfig['lang'])) {
            $this->lang = '\ABC\Abc\Components\Debugger\Lang\\'. $сonfig['lang'];
        } 
    }
    
    /**
    * Активация дебаггера
    *
    * $param string $sql
    *
    * @return void
    */     
    public function run($sql, $result)
    {
        if (false === $result || $this->db->test) {
          
            if (false === $result) {
                $this->errorReport($sql);
            } else {
                $this->testReport($sql);
            }  
        }
    } 
    
    /**
    * Формирует отчет обо ошибке SQL запроса
    *
    * @param string $file
    * @param int $line    
    * @param string    $sql
    * @param string    $error
    *
    * @return void
    */        
    public function errorReport($sql)
    {
        $raw = $this->prepareSqlListing($sql, $this->db->error);
    
        if (!empty($this->lang)) {
            $class = $this->lang;
            $error = $class::translateSql($this->db->error);           
        } else {
            $error = $this->db->error;
        }
        
        $data = ['message' => 'Component '. $this->component .': <b>'. $this->message .'</b>',
                 'error'   => $error,
                 'num'     => $raw['num'],
                 'sql'     => $raw['sql'],
                 'explain' => $this->explain,
                 'php'     => $this->preparePhp(),
                 'file'    => $this->file,
                 'line'    => $this->line,
        ];
        
        $this->view->createReport($data);
        die;
    } 
    
    /**
    * Тест запроса
    *
    * @param string $file
    * @param int $line    
    * @param string    $sql
    * @param string    $error
    *
    * @return void
    */       
    public function testReport($sql)
    {    
        $this->message = null;
        $start = microtime(true);       
        $this->db->rawQuery($sql); 
        $time = sprintf("%01.4f", microtime(true) - $start);
        $this->explain = $this->performExplain($sql, $time);
        $this->errorReport($sql);        
    }

    /**
    * Подготавливает листинг SQL
    *
    * @param string $sql
    * @param string $error
    *
    * @return array
    */    
    protected function prepareSqlListing($sql, $error = '')
    { 
        $sql   = htmlSpecialChars($sql);
        $error = htmlSpecialChars($error);
        
        if (!empty($error)) {
            preg_match("#'(.+?)'#is", $error, $location);
            
            if (!empty($location[1])) {
                $sql = $this->view->highlightLocation($sql, $location[1]);
            }
        }
        
        $cnt = substr_count($sql, "\r") + 2;
        $num = range(1, $cnt);
        return ['num' => $num, 'sql' => $sql];
    }
 
    /**
    * Выполняет EXPLAIN запроса
    *
    * @param string $sql
    * @param string $time
    *
    * @return null
    */    
    protected function performExplain($sql, $time)
    {    
        if (!preg_match('~^select.+~is', trim($sql))) {
            return null;
        }
        
        $res = $this->db->rawQuery("EXPLAIN PARTITIONS ". $sql);
        
        if (is_object($res)) {
         
            if ($this->component === 'Mysqli') {
                $data = $res->fetch_array(MYSQLI_ASSOC);         
            } else {
                $res->setFetchMode();
                $data = $res->fetch();
            }
            
            $data['queryTime'] = $time;
            return $this->view->createExplain($data);
        }
        
        return null;
    } 
 
    /**
    * Формирует проблемный участок PHP кода 
    *
    * @param array $trace
    *
    * @return null
    */    
    protected function preparePhp()
    {
        $php = '';
        $i = 0;
        $block = $this->trace[0]; 
        
        if (basename($block['file']) === 'Shaper.php') {
            $block = $this->trace[1]; 
        }
        
        $this->file = $block['file'];
        $this->line = $block['line'];
        $script = file($block['file']);
        $ext = ceil($this->sizeListing / 2);
        $position = ($block['line'] <= $ext) ? 0 : $block['line'] - $ext;
        
        foreach ($script as $string) {
            ++$i;
         
            if($i == $block['line']) {
                $lines[] = $this->view->wrapLine($i, 'error');
            } elseif($i == $block['line']) {
                $lines[] = $this->view->wrapLine($i, 'trace');
            }
            else {
                $lines[] = $i;
            }
            
            $php .= $string;
        } 
       
        $data['num'] = array_slice($lines, $position, $this->sizeListing);
        $data['total'] = $this->view->highlightString($php, $position, $this->sizeListing);
        $cnt = substr_count($data['total'], "\r") + 2;
        return $this->view->createPhp($data);
    }
}
