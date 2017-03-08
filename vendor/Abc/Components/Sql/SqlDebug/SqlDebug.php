<?php

namespace ABC\Abc\Components\Sql\SqlDebug;

use ABC\Abc\Components\Sql\SqlDebug\View;

/** 
 * Class SqlDedug
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2016
 * @license WTFPL (http://www.wtfpl.net)
 */  
class SqlDebug
{
   
    public $sizeListing = 30; 
    public $db;   
    public $trace;
    

    protected $view; 
    protected $explain;    
    protected $file;
    protected $line;
    protected $message = 'SQL error: ';    
    
    public function __construct()
    { 
        $this->view = new View;
    }
    
    /**
    * Start debug
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
    * It generates a report on a SQL query error
    *  
    * @param string $sql
    *
    * @return void
    */        
    public function errorReport($sql)
    {
        $raw = $this->prepareSqlListing($sql, $this->db->error);
        $error = $this->db->error;
        
        $data = ['message' => $this->component .': <b>'. $this->message .'</b>',
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
    * Test query
    *
    * @param string $sql
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
    * Prepares listing SQL
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
    * EXPLAIN
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
         
            $data = $res->fetch_array(MYSQLI_ASSOC);
            $data['queryTime'] = $time;
            return $this->view->createExplain($data);
        }
        
        return null;
    } 
 
    /**
    * Generates problematic PHP code section 
    *
    * @return string
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
