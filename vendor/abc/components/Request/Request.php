<?php

class Request
{

    private $_variables;

    function __construct()
    {
        
        $this->init();

        $this->validateInput();

        RequestRegistry::setRequest($this);

    }

    function init()
    {

        $this->_variables['server'] = $_SERVER;

        if($_GET) {
            $this->_variables['get'] = $_GET;
        }

        if($_POST) {
            $this->_variables['post'] = $_POST;
        }

        if($_COOKIE) {
            $this->_variables['cookie'] = $_COOKIE;
        }

        if($_FILES) {
            $this->_variables['files'] = $_FILES;
        }

    }

    function getValue($regKey, $valKey, $secKey = null)
    {
        $this->secKey($regKey, $secKey);
        if (isset($this->_variables[$regKey])) {
            return $this->_variables[$regKey][$valKey];
        }
    }

    function setValue($regKey, $valKey, $val, $secKey = null)
    {
        $this->secKey($regKey, $secKey);
        $this->_variables[$regKey][$valKey] = $val;
    }

    private function secKey($regKey, $secKey)
    {
        if(in_array($regKey, array('server', 'cookie')) ){
            if ($secKey != '123') {
                throw new Exception('Secret key is missing');
            }
        }
    }

    private function validateInput(){

        foreach ($this->_variables as $regKey=>$vars) {

            if(in_array($regKey, array('get', 'post'))) {

                foreach ($vars as $key=>$val){
                   if (!$this->validateValue($key, $val)) {
                       throw new Exception('Input is not valid');
                   };
                }

            }

        }
    }

    private function validateValue($key, $val){

        switch ($key){
            case 'id':
                if(!is_numeric($val)) {
                    return false;
                }
                break;
            default:
                break;
        }

        return true;

    }
}