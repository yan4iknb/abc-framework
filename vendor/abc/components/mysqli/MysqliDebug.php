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
    protected $view;    
    protected $mysqli;
    
    public function __construct($mysqli, $view)
    { 
        $this->mysqli = $mysqli->db;
        $this->view = $view;
    }
    
    public function errorReport($file, $line, $sql, $error = '')
    { 
        $raw = $this->prepareSql($sql, $error);
        $data = ['mess'    => $this->mess,
                 'file'    => $file,
                 'line'    => $line,
                 'error'   => htmlSpecialChars($error),
                 'num'     => $raw['num'],
                 'sql'     => $raw['sql']
        ];
        
        $this->view->createReport($data);
    } 
    
    public static function testReport($file, $line, $sql)
    { 
        $this->mess = 'MySQL query: ';
        $this->errorReport($file, $line, $sql)
     
        $start = microtime(true);
        $this->mysqli->query($sql);
        $data['time'] = sprintf("%01.4f", microtime(true) - $start);
        $this->view->createReport($data);
        $this->explain($sql);
    }

    protected function prepareListing($sql, $error = '')
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
    
    protected function explain($sql)
    {     
        $res = $this->mysqli->query(db::getLink(), "EXPLAIN ". $sql);
        
        if (is_object($res)) {
         
            $data = $res->fetch_array(MYSQLI_ASSOC);
            $this->view->createExplain($data);
        }
    } 
}









