<?php

namespace ABC\abc\components\dbdebug;

class Dbdedug
{
   
    public $sizeListing = 30; 
    public $db; 
    public $type;    
    
    protected $message = 'MySQL error: ';
    
    /**
    * @var View
    */
    protected $view; 

    /**
    * @var string
    */
    protected $explain;
    
    /**
    * Конструктор
    *
    * @param object $mysqli
    * @param object $view
    */        
    public function __construct($view)
    { 
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
    public function errorReport($trace, $sql, $error = '')
    { 
        $raw = $this->prepareSqlListing($sql, $error);
        
        $data = ['message' => $this->message,
                 'file'    => $trace[0]['file'],
                 'line'    => $trace[0]['line'],
                 'error'   => htmlSpecialChars($error),
                 'num'     => $raw['num'],
                 'sql'     => $raw['sql'],
                 'explain' => $this->explain,
                 'php'     => $this->preparePhp($trace)
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
    public function testReport($trace, $sql, $error = '')
    { 
        $this->message = 'MySQL query: ';
        $start = microtime(true);        
        $this->db->query($sql);
        $time = sprintf("%01.4f", microtime(true) - $start);
        $this->explain = $this->performExplain($sql, $time);
        $this->errorReport($trace, $sql, $error = '');        
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
        $res = $this->db->query("EXPLAIN ". $sql);
        
        if (is_object($res)) {
         
            if ($this->type === 'mysqli') {
                $data = $res->fetch_array(MYSQLI_ASSOC);         
            }
            elseif ($this->type === 'pdo') {
                $data = $res[0];
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
    protected function preparePhp($trace)
    { 
        $php = '';
        $i = 0;
        $block = $trace[0]; 
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

