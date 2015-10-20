<?php

namespace ABC\abc\components\mysqli;

use ABC\abc\components\mysqli\MysqliDebug;
use ABC\abc\components\mysqli\View;
/** 
 * Класс Mysqli
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */  

class Mysqli 
{
/**
 * @var Mysqli
 */ 
    public $db;
    
    public $test = false;
    
/**
 * @var string
 */     
    protected $host;
    
/**
 * @var string
 */ 
    protected $user;
    
/**
 * @var string
 */ 
    protected $pass;
    
/**
 * @var string
 */ 
    protected $base;

/**
 * @var string
 */ 
    protected $view;
    
/**
 * Конструктор
 *
 * @param array $connectData
 */      
    public function __construct($connectData = [])
    {        
        if (!empty($connectData)) {
         
            extract($connectData);
            
            if (!isset($host, $user, $pass, $base)) {
                trigger_error('Wrong data connection in the configuration file', E_USER_WARNING);
            }
            
            $this->newConnect($host, $user, $pass, $base);
        }
        
        $this->view = new View;
        
    }
    
/**
 * Новый коннект
 *
 * @param string $host
 * @param string $user
 * @param string $pass
 * @param string $base
 *
 * @return void
 */    
    public function newConnect($host = '', $user = '', $pass = '', $base = '')
    {
        if (empty($host) || empty($user) || empty($base)) {
            trigger_error('Incorrect data connect', E_USER_WARNING);
        }
        
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->base = $base;
        $this->connect();
    } 
    
    
/**
 * Обертка для query()
 *
 * @return void
 */     
    public function query($sql)
    {
        $result = $this->db->query($sql);
        
        if (false === $result || $this->test) {
         
            $error = $this->db->error;
            $trace = debug_backtrace();
            $debug = new MysqliDebug($this->db, $this->view);
            
            if ($this->test) {
                $debug->testReport($trace, $sql, $error);
            }
            else {
                $debug->errorReport($trace, $sql, $error);
            }
        }
        
        return $result;
    } 
    
/**
 * Инициализирует объект Mysqli
 *
 * @return void
 */     
    protected function connect()
    {
        $this->db = @new \Mysqli($this->host, $this->user, $this->pass, $this->base);
      
        if ($this->db->connect_error) {
            trigger_error('<b>MySQLi error:</b> '. $this->db->connect_error, E_USER_WARNING);
        }
        
        $this->db->set_charset("utf8");
    } 

}








