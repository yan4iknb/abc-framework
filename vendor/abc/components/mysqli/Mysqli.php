<?php

namespace ABC\abc\components\mysqli;

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
 * @var object
 */ 
    public $db;
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
        return $this->db->query($sql);
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








