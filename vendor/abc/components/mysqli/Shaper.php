<?php

namespace ABC\Abc\Components\Mysqli;

/** 
 * Класс Shaper
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */  
class Shaper extends \mysqli_stmt
{
    /**
    * @var ABC\Abc\Components\Mysqli\Mysqli
    */  
    protected $mysqli;

    protected $rawSql;    
    protected $debugTypes;
    protected $debugVars;
    
    /**
    * Конструктор
    *
    * @param string $sql
    *    
    */     
    public function __construct($mysqli, $sql)
    {
        $this->mysqli = $mysqli;
        $this->rawSql = $sql;
        parent::__construct($mysqli, $sql);
    }
    
    /**
    * Подготавливает параметры для запроса.
    *
    * @param string $sql
    *    
    * @return void
    */     
    public function bind_param($types, &...$vars)
    {    
        $this->debugTypes = $types;
        $this->debugVars  = $vars;
    }
    
    /**
    * Выполняет запрос.
    *    
    * @return void
    */     
    public function execute()
    { 
        $types  = str_split($this->debugTypes);
        $params = ['types' => $types,
                   'vars'  => $this->debugVars
               ];
        
        $this->mysqli->autocommit(false);  
        $sql = $this->createSqlString($params); 
        $this->mysqli->query($sql);
        $this->mysqli->rollback();
        
        if (empty($this->mysqli->error)) {        
            $bindParams = $this->boundParams($params);
            call_user_func_array(['parent', 'bind_param'], $bindParams);
            parent::execute();
            $this->mysqli->commit();   
        } 
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
    protected function createSqlString($params)
    {
        $values = [];
        $sql = $this->rawSql;
        
        foreach ($params['types'] as $k => $type) {
            $value = $this->escape($params['vars'][$k], $type);
            $sql = preg_replace('#\?#', $value, $sql, 1);            
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
