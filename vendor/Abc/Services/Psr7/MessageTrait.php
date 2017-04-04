<?php

namespace ABC\Abc\Services\Psr7;

use ABC\Abc\Core\Exception\AbcError;

/** 
 * Трэйт Message
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/
 */   
trait MessageTrait
{
    protected $storage;

    protected static $validProtocol = [
                '1.0' => 1,
                '1.1' => 1,
                '2.0' => 1,
    ];
    
    protected static $special = [
                'CONTENT_TYPE'    => 1,
                'CONTENT_LENGTH'  => 1,
                'PHP_AUTH_USER'   => 1,
                'PHP_AUTH_PW'     => 1,
                'PHP_AUTH_DIGEST' => 1,
                'AUTH_TYPE'       => 1,
    ];    

    
    public function __set($name, $value) {}
    
    /**
    * Реакция на неподдерживаемые методы
    */ 
    public function __call($method, $params)
    {
        AbcError::BadMethodCall('<strong>'
                               . $method 
                               .'</strong>'
                               . ABC_NO_METHOD
        );
    }

    /**
    * Возвращает версию протокола HTTP в виде строки
    */ 
    public function getProtocolVersion()
    {
        return $this->storage->get('protocolversion');
    }
    
    /**
    * Возвращает новый объект с установленным HTTP протоколом
    *
    * @param string $version
    *
    * @return object
    */ 
    public function withProtocolVersion($version)
    {
        if (!isset(self::$validProtocol[$version])) {
            AbcError::InvalidArgument(ABC_INVALID_PROTOCOL);
            return false;
        }
        
        $this->storage->add('protocolversion', $version);
        return clone $this;
    }
    
    /**
    * Возвращает все заголовки
    *
    * @return array
    */ 
    public function getHeaders()
    {
        return $this->storage->all('headers');
    }
    
    /**
    * Проверяет наличие заголовка
    */ 
    public function hasHeader($name)
    {
        return $this->storage->has($name, 'headers');
    }
    
    /**
    * Возвращает значение заголовка по имени
    *
    * @param string $name
    *
    * @return string|array
    */ 
    public function getHeader($name)
    { 
        if (!$this->storage->has($name, 'headers')) {
            return [];
        }
        
        $value  = $this->storage->get($name, 'headers');
        $value  = is_array($value) ? $value : [$value];
        return array_shift($value);
    }
    
    /**
    * Получает строку значений заголовка, разделенными запятыми.
    *
    * @param string $name
    *
    * @return string
    */ 
    public function getHeaderLine($name)
    {
        $value = $this->getHeader($name);
     
        if (!is_array($value)) {
            $value = [$value];
        }
        return implode(', ', $value);
    }
    
    /**
    * Возвращает новый объект с новым или замененным заголовком.
    *
    * @param string $name
    * @param string $value
    *
    * @return object
    */ 
    public function withHeader($name, $value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        
        if (!$this->checkupArray($value)) {
            AbcError::InvalidArgument(ABC_VALUE_NO_STRING);
            return false;
        }
        
        if ($this->validateName($name) && $this->validateValue($value)) {
            $this->storage->add($name, $value, 'headers');
            return clone $this;; 
            
        } else {
            return false;
        }
    }
    
    /**
    * Возвращает новый объект с добавленными заголовками.
    *
    * @param string $name
    * @param string $value
    *
    * @return object
    */    
    public function withAddedHeader($name, $value)
    {
        $value = is_array($value) ? $value : [$value];    
    
        if (!$this->checkupArray($value)) {
            AbcError::InvalidArgument(ABC_VALUE_NO_STRING);
            return false;
        }
        
        if ($this->validateName($name) && $this->validateValue($value)) {        
            $this->storage->merge($name, $value, 'headers');
            return clone $this;
        } else {
            return false;
        }
    }
    
    /**
    * Возвращает новый объект с удаленным заголовками.
    *
    * @param string $name
    *
    * @return object
    */   
    public function withoutHeader($name)
    {
        if (!$this->hasHeader($name, 'headers')) {
            AbcError::InvalidArgument($name . ABC_NO_HEADER);
            return false;
        }
        
        $this->storage->delete($name, 'headers');
        return clone $this;
    }
    
    /**
    * Возвращает тело сообщения
    *
    * @return object
    */ 
    public function getBody()
    {
        return $this->storage->get('body');
    }
    
    /**
    * Возвращает новый объект с новым телом сообщения.
    *
    * @param object $name
    *
    * @return object
    */ 
    public function withBody($body)
    {
        $this->storage->add('body', $body);
        return clone $this;
    }
    
/*-----------------------------------------------------    
        Хэлперы
-------------------------------------------------------*/
    /**
    * Стартовая инициализация
    *
    * @param array $headers
    */ 
    protected function initialize($headers = [])
    {
        $this->storage = $this->abc->newService('Storage');
        
        $env = $this->abc->getConfig('environment');
        $this->method = $env['REQUEST_METHOD'];
        $this->storage->add('serverParam', $env);
        
        $protocolVersion = str_replace('HTTP/', '', $env['SERVER_PROTOCOL']);
        $this->storage->add('protocolversion', $protocolVersion);
        
        $this->setHeaders($headers);
    }
    
    /**
    * Устанавливает заголовки
    *
    * @param array $headers
    */ 
    protected function setHeaders($headers = [])
    {
        $headers = !empty($headers) ? $headers : $this->envHeaders();
        $headers = $this->filterHeaders($headers);
        
        foreach ($headers as $key => $value) {
            $this->storage->add($key, $value, 'headers');           
        }
    }
    
    /**
    * Устанавливает дефолтные заголовки
    * 
    * @return array 
    */ 
    protected function envHeaders()
    {
        $env = $this->storage->get('serverParam');
        $headers = [];
        foreach ($env as $key => $value) {
            $keyUpper = strtoupper($key);            
            
            if (isset(self::$special[$keyUpper]) || strpos($keyUpper, 'HTTP_') === 0) {
             
                if ($keyUpper !== 'HTTP_CONTENT_LENGTH') {
                    $headers[$key] = $value;
                }
            }
        }
        
        return $headers;
    }  

    /**
    * Фильтрует и нормализует заголовки.
    *
    * @param array $header
    * 
    * @return array 
    */
    private function filterHeaders($headers)
    {
        $out = [];
        foreach ($headers as $header => $value) {
            if (!is_string($header)) {
                continue;
            }
         
            if (!is_array($value) && !is_string($value)) {
                continue;
            }
         
            if (!is_array($value)) {
                $value = [$value];
            }
         
            $out[$header] = $value;
        }
      
        return $out;
    }
    
    /**
    * Проверка значений массива на строковый тип
    *
    * @param array $array
    *
    * @return bool
    */
    protected function checkupArray($array)
    {
        return array_reduce($array, function ($carry, $item) {
            if (!is_string($item)) {
                return false;
            }
            return $carry;
        }, true);
    }
    
    /**
    * 
    *
    * @param mixed $name
    * 
    * @return bool
    */
    protected function validateName($name)
    {
        if (!preg_match('/^[a-zA-Z0-9\'`#$%&*+.^_|~!-]+$/', $name)) {
            AbcError::InvalidArgument($name . ABC_INVALID_HEADER_NAME);
            return false;
        }
        
        return true;
    }
    
    /**
    * Assert that the provided header values are valid.
    *
    * @param array $values
    *
    * @return bool
    */
    protected function validateValue($values)
    {
        foreach ($values as $value) {
         
            if (!$this->isValidValue($value)) {
                AbcError::InvalidArgument($value . ABC_INVALID_HEADER_VALUE);
                return false;
            }
        }
        
        return true;
    }
    
    /**
    * 
    *
    * @param array $value
    *
    * @return bool
    */
    protected function isValidValue($value)
    {
        $value = (string)$value;
     
        if (preg_match("#(?:(?:(?<!\r)\n)|(?:\r(?!\n))|(?:\r\n(?![ \t])))#", $value)) {
            return false;
        }
     
        $length = strlen($value);
        for ($i = 0; $i < $length; $i++) {
            $ascii = ord($value[$i]);
         
            if (($ascii < 32 && !in_array($ascii, [9, 10, 13], true))
                || $ascii === 127
                || $ascii > 254
            ) {
                return false;
            }
        }
     
        return true;
    } 
}
