<?php

namespace ABC\abc\components\mysqli;

/** 
 * Класс MysqliDebug
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */  

class MysqliDebug
{

    protected $mess = 'MySQL error: ';
    
    /**
    * @var object
    */
    protected $view; 
    
    /**
    * @var object
    */
    protected $mysqli;
    
    /**
    * Конструктор
    *
    * @param object $mysqli
    * @param object $view
    */        
    public function __construct($mysqli, $view)
    { 
        $this->mysqli = $mysqli->db;
        $this->view = $view;
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
    public function errorReport($file, $line, $sql, $error = '')
    { 
        $raw = $this->prepareSqlListing($sql, $error);
        $data = ['mess'    => $this->mess,
                 'file'    => $file,
                 'line'    => $line,
                 'error'   => htmlSpecialChars($error),
                 'num'     => $raw['num'],
                 'sql'     => $raw['sql']
        ];
        
        $this->view->createReport($data);
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
    public function testReport($file, $line, $sql, $error = '')
    { 
        $this->mess = 'MySQL query: ';
        $this->errorReport($file, $line, $sql, $error = '');
        $start = microtime(true);        
        $this->mysqli->query($sql);
        $data['time'] = sprintf("%01.4f", microtime(true) - $start);
        $data['explain'] = $this->explain($sql);
        $this->view->createExplain($data);
        
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
        
        $cnt = substr_count($sql, "\r") + 1;
        $num = array_fill(1, $cnt, true);
        return ['num' => $num, 'sql' => $sql];
    }
 
    /**
    * Выполняет EXPLAIN запроса
    *
    * @param string $sql
    *
    * @return null
    */    
    protected function explain($sql)
    {     
        $res = $this->mysqli->query(db::getLink(), "EXPLAIN ". $sql);
        
        if (is_object($res)) {
         
            $data = $res->fetch_array(MYSQLI_ASSOC);
            return $this->view->createExplain($data);
        }
        
        return null;
    } 
}









