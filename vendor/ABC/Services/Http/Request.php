<?php

namespace ABC\ABC\Services\Http;

use ABC\ABC\Core\Exception\AbcError;

/** 
 * Класс Request
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/
 */   
class Request extends RequestAddition
{
    use MessageTrait;

    protected $abc;
    protected $storage;
    protected static $validMethods = [
        'GET'     => true,    
        'POST'    => true,
        'PUT'     => true,
        'DELETE'  => true,
        'CONNECT' => true,
        'HEAD'    => true,
        'OPTIONS' => true,
        'PATCH'   => true,
        'TRACE'   => true,
    ];  
        
    /**
    * Конструктор
    */ 
    public function __construct($abc)
    {
        $this->abc = $abc;  
        $this->storage = $abc->newService(\ABC\ABC::STORAGE);
        $this->storage->add('serverParams', $_SERVER);
        $this->setEnvHeaders($_SERVER);
        $this->storage->add('Method', $_SERVER['REQUEST_METHOD']);
        $this->storage->add('uri', new Uri($this->abc));
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
        $env = $this->abc->getEnvironment();    
        
        if (isset($serverParams['SERVER_PROTOCOL'])) {
            $protocolVersion = $serverParams['SERVER_PROTOCOL'];
        } else {
            $protocolVersion = $env['SERVER_PROTOCOL'];
        }

        $new->storage->add('protocolversion', str_replace('HTTP/', '', $protocolVersion));
        
        if (null === $method) {
            $method = $env['REQUEST_METHOD'];
        }   
        
        $new->storage->add('method', $new->filterMethod($method));
        
        if (is_string($uri)) {
            $new->storage->add('uri', new Uri($this->abc, $uri));
        } elseif ($uri instanceof Uri) {
            $new->storage->add('uri', $uri);
        } else {
            $new->storage->add('uri', new Uri($this->abc));
        }
        
        if (null !== $headers) {
            $new->setHeaders($headers);
        } else {
            $new->setEnvHeaders($env);
        }
        
        if (null !== $serverParams) {
            $new->storage->add('serverParams', $env);
        } else {
            $new->storage->add('serverParams', $serverParams);        
        }
        
        if (null !== $cookies) {
            $new->storage->add('cookies', $cookies);
        }
        
        if (null !== $body && $body instanceof Stream) {
            $new->storage->add('body', $body);
        } else {
            $new->storage->add('body', new Stream('php://memory', 'r')); 
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
        return $this->storage->get('Method');
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
        return $this->storage->get('uri'); 
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
        
        $new = clone $this;
        
        if (!$preserveHost) {
         
            if ($uri->getHost() !== '') {
                $new->storage->add('host', $uri->getHost());
            }
            
        } else {
         
            if ($uri->getHost() !== '' && (!$this->hasHeader('host') || $this->getHeaderLine('host') === '')) {
                $new->storage->add('host', $uri->getHost());
            }
        }
        
        $new->storage->add('uri', $uri);
        return $new;
    }
    
/*-----------------------------------------------------    
        ServerRequest
-------------------------------------------------------*/
    /**
    * Получает параметры сервера ($_SERVER).
    *
    * @return array
    */
    public function getServerParams()
    {
        return $this->storage->get('serverParams');
    }

    /**
    * Получает куки ($_COOKIE).
    *
    * @return array
    */
    public function getCookieParams()
    {
        return $this->storage->get('cookieParams');
    }

    /**
    * Возвращает новый объект с установленными куками ($_COOKIE).
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
    * Возвращает параметры query string ($_GET) в виде массива.
    *
    * @return array
    */
    public function getQueryParams()
    {
        $queryParams = $this->storage->get('queryParams');
      
        if (is_array($queryParams)) {
            return $queryParams;
        }
     
        if (!$this->storage->has('uri')) {
            return [];
        }
     
        $uri = $this->storage->get('uri');
        parse_str($uri->getQuery(), $queryParams); 
        $this->storage->add('queryParams', $queryParams);
        
        return $this->storage->get('queryParams');
    }

    /**
    * Возвращает новый объект с установленной query string ($_GET).
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
    * Возвращает нормализованные данные для загрузки файлов ($_FILES).
    *
    * @return array 
    */
    public function getUploadedFiles()
    {
        return $this->storage->get('uploadedFiles');
    }

    /**
    * Возвращает новый объект с указанными загруженными файлами ($_FILES).
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
    * Возвращает все параметры из body ($_POST).
    *
    * @return null|array|object 
    */
    public function getParsedBody()
    {
        return $this->storage->get('parsedBody');
    }

    /**
    * Возвращает новый объект с указанным параметром body ($_POST).
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
            AbcError::badMethodCall('<strong>'
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
}
