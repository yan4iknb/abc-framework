<?php

namespace ABC\Abc\Components\Pdo;

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
                                'type'  => $datatype
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
     
        return parent::execute($params);
    }
    
    /**
    * Связывает типы с параметрами.
    *    
    * @return array
    */ 
    protected function boundParams($params)
    {
        $bound    = [];
        $bound[0] = '';
        
        foreach ($params['types'] as $k => $type) {
            $bound[0] .= $type;
            $bound[]   = &$params['vars'][$k];
        }
     
        return $bound;
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
        
        if ($params) {
            ksort($params);
            foreach ($params as $key => $value) {
                $replace = (is_array($value)) ? $value
                                              : ['value' => $value,
                                                 'type'  => PDO::PARAM_STR ];
                $replace = $this->prepareValue($replace);
                $sql     = $this->replaceMarker($sql, $key, $replace);
            }
        }

        return $sql;
    }
    
    /**
    * Обрабатывает параметры для дебаггинга в зависимости от типа.
    *
    * @param string $param
    * @param string $type
    *    
    * @return string
    */     
    protected function escape($param, $type)
    {    
        switch ($type) {
            case 'i' :
                return (int)$param;
            
            case 'd' :
                return "'". (float)$param ."'";
            
            case 's' :
            case 'b' :
                return "'". addslashes($param) ."'";
            
            default :
                throw new \InvalidArgumentException('<b>Component Mysqli</b>: unknown type of the parameter <b>'. $type .'</b>', 
                                                    E_USER_WARNING);    
        }   
    }
}
