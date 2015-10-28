<?php

namespace ABC\Abc\Request\Request;

class RequestRegistry extends Registry
{

    private $_values = array();

    private static $_instance;

    private function __construct(){}

    static function instance(){
        if(!isset(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    protected function get ($key){
        if (isset($this->_values[$key])){
            return $this->_values[$key];
        }
        return null;
    }

    protected function set ($key, $val){
        $this->_values[$key] = $val;
    }

     static function getRequest (){
        return self::instance()->get('request');
    }

    static function setRequest (Request $request){
         self::instance()->set('request', $request);
    }

}