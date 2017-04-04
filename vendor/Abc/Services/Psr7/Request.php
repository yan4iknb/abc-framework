<?php

namespace ABC\Abc\Services\Psr7;

use ABC\Abc\Core\Exception\AbcError;

/** 
 * Класс Request
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/
 */   
class Request
{
    use MessageTrait;

    protected $abc;
    protected $method; 
    protected static $validMethods = [
        'CONNECT' => true,
        'DELETE'  => true,
        'GET'     => true,
        'HEAD'    => true,
        'OPTIONS' => true,
        'PATCH'   => true,
        'POST'    => true,
        'PUT'     => true,
        'TRACE'   => true,
    ];  
        
    /**
    * Конструктор
    */ 
    public function __construct($abc)
    {
        $this->abc = $abc;
        $this->initialize();
        $this->storage->add('body', new Stream('php://memory', 'r'));
    }
    
    /**
    * Создает новый объект Request
    *
    * @return object 
    */
    public function newRequest($method        = null, 
                               $uri           = null, 
                         array $headers       = null, 
                         array $cookies       = null,
                         array $serverParams  = null,
                               $body          = null, 
                         array $uploadedFiles = []
    ) {
        $new = new Request();
        
        if (null === $method) {
            $method = $this->method;
        }            
     
        $new->storage->add('method', $this->filterMethod($method));
        
        if (is_string($uri) && $uri instanceof Uri) {
            $new->uri = $uri;
        }
        
        if (null !== $headers) {
        
            foreach ($headers as $key => $value) {
             
                if (is_array($headers)) {                
                    $new->withHeader(key($value), array_shift($value));                
                } else {
                    $new->withHeader($key, $value);    
                }
            }
        }
        
        if (null !== $serverParams) {
            $new->storage->add('serverParams', $serverParams);
        }
        
        if (null !== $cookies) {
            $new->storage->add('cookies', $cookies);
        }
        
        if (null !== $body) {
            $new->storage->add('body', $body);
        }
     
        $new->storage->add('uploadedFiles', $uploadedFiles);
        return $new;   
    }     
    
    
    /**
    * Инициализация при клонировании
    */ 
    public function __clone() 
    {
        $this->storage = clone $this->storage;
    }

    /**
    * Возвращает цель запроса
    *
    * @return string
    */
    public function getRequestTarget()
    {
        if (!$this->ctorage->has('requestTarget')) {
            return $this->ctorage->get('requestTarget');
        }
     
        if (!$this->ctorage->has('uriObject')) {
            return '/';
        }
        
        $target = $this->ctorage->get('uriObject')->getPath();
        $query  = $this->ctorage->get('uriObject')->getQuery();
        
        if (!empty($query)) {
            $target .= '?' . $query;
        }
      
        if (empty($target)) {
            $target = '/';
        }
     
        return $target;
    }

    /**
    * Возвращает новый объект с установленной целью запроса.
    *
    * @param mixed $requestTarget
    *
    * @return object
    */
    public function withRequestTarget($requestTarget)
    {
        if (preg_match('#\s#', $requestTarget)) {
            AbcError::invalidArgument(ABC_INVALID_TARGET);
            return false;
        }
        
        $this->storage->add('target', $requestTarget);
        return clone $this;
    }

    /**
    * Возвращает HTTP метод для запроса.
    *
    * @return string 
    */
    public function getMethod()
    {
        return $this->ctorage->get('Method');
    }

    /**
    * Возвращает новый объект с установленным HTTP методом.
    *
    * @param string $method 
    *
    * @return object
    */
    public function withMethod($method)
    {
        $this->storage->add('method', $this->filterMethod($method));
        return clone $this;
    }

    /**
    * Возвращает объект Uri.
    *
    * @return object
    */
    public function getUri()
    {
        if (!$this->storage->has('UriObject')) {
            $this->storage->add('UriObject', (new Uri));
        }
        
        return $this->storage->get('UriObject'); 
    }

    /**
    * Возвращает новый объект с установленным Uri
    *
    * @param UriInterface $uri
    * @param bool $preserveHost
    *
    * @return static
    */
    public function withUri($uri, $preserveHost = false)
    {
        if (!$uri instanceof Uri) {
            AbcError::invalidArgument(ABC_OTHER_OBJECT);
            return false;
        }
     
        if (!$preserveHost) {
         
            if ($uri->getHost() !== '') {
                $this->storage->add('host', $uri->getHost());
            }
            
        } else {
         
            if ($uri->getHost() !== '' && (!$this->hasHeader('host') || $this->getHeaderLine('host') === '')) {
                $this->storage->add('host', $uri->getHost());
            }
        }
        
        $this->storage->add('UriObject', $uri);
        return clone $this;
    }
    
/*-----------------------------------------------------    
        ServerRequest
-------------------------------------------------------*/
    /**
    * Получает параметры сервера (SERVER).
    *
    * @return array
    */
    public function getServerParams()
    {
        return $this->storage->get('serverParams');
    }

    /**
    * Получает куки (COOKIE).
    *
    * @return array
    */
    public function getCookieParams()
    {
        return $this->storage->get('cookieParams');
    }

    /**
    * Возвращает новый объект с установленными куками (COOKIE).
    *
    * @param array $cookies 
    *
    * @return static
    */
    public function withCookieParams(array $cookies)
    {
        $this->storage->add('cookieParams', $cookies);
        return clone $this;
    }

    /**
    * Возвращает параметры query string (GET) в виде массива.
    *
    * @return array
    */
    public function getQueryParams()
    {
        return $this->storage->get('queryParams');
    }

    /**
    * Возвращает новый объект с установленной query string (GET).
    *
    * @param array $query 
    *
    * @return object
    */
    public function withQueryParams(array $query)
    {
        $this->storage->add('query', $query);
        return clone $this;
    }

    /**
    * Возвращает нормализованные данные для загрузки файлов (FILES).
    *
    * @return array 
    */
    public function getUploadedFiles()
    {
        return $this->storage->get('uploadedFiles');
    }

    /**
    * Возвращает новый объект с указанными загруженными файлами (FILES).
    *
    * @param array $uploadedFiles
    *
    * @return object
    */
    public function withUploadedFiles(array $uploadedFiles)
    {
        $this->validateUploadedFiles($uploadedFiles);
        $this->storage->add('uploadedFiles', $uploadedFiles);
        return clone $this;
    }

    /**
    * Возвращает все параметры из body (POST).
    *
    * @return null|array|object 
    */
    public function getParsedBody()
    {
        return $this->storage->get('parsedBody');
    }

    /**
    * Возвращает новый объект с указанным параметром body (POST).
    *
    * @param null|array|object $data 
    *
    * @return object
    */
    public function withParsedBody($data)
    {
        $this->storage->add('parsedBody', $data);
        return clone $this;
    }

    /**
    * Возвращает все атрибуты текщего запроса.
    *
    * @return array 
    */
    public function getAttributes()
    {
        return $this->storage->get('attributes');
    }

    /**
    * Возвращает один атрибут по указанному имени.
    *
    * @param string $name 
    * @param mixed $default
    *
    * @return mixed
    */
    public function getAttribute($name, $default = null)
    {
        if (!$this->storage->has($name, 'attributes')) {
            return $default;
        }
        
        return $this->storage->get($name, 'attributes');
    }

    /**
    * Возвращает объект, в котором добавлен указанный атрибут.
    *
    * @param string $name
    * @param mixed $value 
    *
    * @return object
    */
    public function withAttribute($name, $value)
    {
        $this->storage->add([$name => $value], 'attributes');
        return clone $this;
    }

    /**
    * Возвращает объект, в котором удален указанный атрибут текущего запроса.
    *
    * @param string $name
    *
    * @return object
    */
    public function withoutAttribute($name)
    {
        $this->storage->delete($name, 'attributes');
        return clone $this;
    }
    
/*-----------------------------------------------------    
        Хэлперы
-------------------------------------------------------*/    

    /**
    * Валидация HTTP методов
    *
    * @param  null|string $method
    *
    * @return null|string
    */
    protected function filterMethod($method)
    {
        if ($method === null) {
            return $method;
        }
     
        if (!is_string($method)) {
            AbcError::BadMethodCall('<strong>'
                       . $method 
                       .'</strong>'
                       . ABC_NO_METHOD
            );
            return false;
        }
     
        $method = strtoupper($method);
        
        if (!isset(self::$validMethods[$method])) {
            throw new InvalidMethodException($this, $method);
        }
     
        return $method;
    }    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
    * Инициализация GET параметров
    *
    * @param string $key
    * @param string $default
    *
    * @return string|array
    */        
    public function get($key = null, $default = null)
    {
        if (empty($key)) {
            return @$this->GET;
        }
        
        return isset($this->GET[$key]) ? $this->GET[$key] : $default;
    } 
    
    /**
    * Инициализация POST параметров 
    *
    * @param string $key
    * @param string $default
    *
    * @return string|array
    */        
    public function post($key = null, $default = null)
    {
        if (empty($key)) {
            return @$_POST;
        }
        
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }   
    
    /**
    * Инициализация параметров COOKIE
    *
    * @param string $key
    * @param string $default
    *
    * @return string
    */        
    public function cookie($key = null, $default = null)
    {
        if (empty($key)) {
            return @$_COOKIE;
        }
        
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $default;
    }
    
    /**
    * Возвращает текущий контроллер
    *
    * @return string
    */    
    public function getController()
    {
        $get = $this->GET;
        return array_shift($get);
    }
    
    /**
    * Возвращает текущий экшен
    *
    * @return string
    */    
    public function getAction()
    {    
        $get = $this->GET;
        array_shift($get);    
        return array_shift($get);
    }
    
    /**
    * Возвращает HOST
    *
    * @return string
    */    
    public function getHostName()
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        } elseif (isset($_SERVER['SERVER_NAME'])) {
            return $_SERVER['SERVER_NAME'];
        }
        
        return null;
    }

    /**
    * Возвращает PATH
    *
    * @return string
    */    
    public function getPath()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            return parse_url($_SERVER['REQUEST_URI'])['path'];        
        } 
        
        return '/';
    }
    
    /**
    * Возвращает текущий протокол
    *
    * @return string
    */ 
    public function getProtocol()
    {
        return (isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] == 1)
               || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0) 
                ? 'https' : 'http';
    }
    
    /**
    * Возвращает базовый URL
    *
    * @return string
    */    
    public function getBaseUrl()
    {
        return $this->getProtocol() .'://'. $this->getHostName();
    }
    
    /**
    * Проверяет, отправлен запрос AJAX'ом или нет
    *
    * @return bool
    */  
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
    
    /**
    * Разбирает в массив QUERY_STRING
    *
    * @return array
    */        
    protected function parseQueryString()
    {
        $queryString = urldecode($_SERVER['QUERY_STRING']);
        mb_parse_str($queryString, $result);
        return $result;
    }
}
