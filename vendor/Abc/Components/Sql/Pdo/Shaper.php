<?php

namespace ABC\Abc\Components\Sql\Pdo;

/** 
 * Класс Shaper
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class Shaper extends \PDOStatement
{
    /**
    * @var ABC\Abc\Components\Pdo\Pdo
    */  
    protected $pdo;

    protected $rawSql;    
    protected $bound;

    
    /**
    * Конструктор
    *
    * @param object $pdo
    * @param string $sql
    *    
    */     
    public function __construct($pdo, $sql)
    {
        $this->pdo = $pdo;
        $this->rawSql = $sql;
    }
    
    /**
    * Подготавливает параметры для запроса.
    *
    * @param mixed $param
    * @param mixed &$value
    * @param int $type
    * @param int $length
    * @param mixed $driver
    *    
    * @return bool
    */
    public function bindParam($param, &$value, $type = PDO::PARAM_STR, $length = 0, $driver = null)
    {
        $this->bound[$param] = ['value' => &$value,
                                'type'  => $type
        ]; 
     
        return parent::bindParam($param, $value, $type, $length, $driver);
    }
    
    /**
    * Подготавливает параметры для запроса.
    *
    * @param mixed $param
    * @param mixed $value
    * @param int $type
    *    
    * @return bool
    */    
    public function bindValue($param, $value, $type = PDO::PARAM_STR)
    {
        $this->bound[$param] = ['value' => $value,
                                'type'  => $type
        ];
     
        return parent::bindValue($param, $value, $type);
    }
    
    /**
    * Выполняет запрос.
    *
    * @param array $params
    *    
    * @return void
    */
    public function execute($params = null)
    {
        $sql = $this->createSqlString($params);
       
        $this->pdo->beginTransaction();
        $this->pdo->query($sql);
        $this->pdo->rollback();
     
        return parent::execute();
    }

    /**
    * Генерирует результирующий SQL.
    *    
    * @return string
    */ 
    protected function createSqlString($params = null)
    {
        $sql = $this->rawSql;
     
        $params = !empty($this->bound) ? $this->bound : $params;
        
        if (!empty($params)) {
            ksort($params);
            foreach ($params as $marker => $param) {
                $replace = (is_array($param)) ? $param
                                              : ['value' => $param,
                                                 'type'  => PDO::PARAM_STR ];
                $replace = $this->escape($replace);
                $sql     = $this->replace($sql, $marker, $replace);
            }
        }
     
        return $sql;
    }
    
    protected function replace($sql, $marker, $replace)
    {
        if (is_numeric($marker)) {
            $marker = '\?';
        } else {
            $marker = (preg_match('/^\:/', $marker)) ? $marker : ':' . $marker;
        }
     
        return preg_replace('#'. $marker .'(?!\w)#', $replace, $sql, 1);
    }
    
    /**
    * Обрабатывает параметры для дебаггинга в зависимости от типа.
    *
    * @param string $param
    *    
    * @return string
    */     
    protected function escape($param)
    {    
        switch ($param['type']) {
            case PDO::PARAM_INT :
                return (int)$param['value'];
            
            default :
                return $this->pdo->quote($param['value']);
        }   
    } 
}
