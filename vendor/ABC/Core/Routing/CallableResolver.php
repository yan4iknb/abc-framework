<?php

namespace ABC\ABC\Core\Routing;

use ABC\ABC;
use ABC\ABC\Core\Base;
use ABC\ABC\Core\Exception\AbcError;

/** 
 * Класс Executor
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/
 */   
class CallableResolver
{
    use ParserTrait;
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
    * @param object $abc
    */ 
    public function __construct($abc)
    {
        $this->abc = $abc;
        $this->storage = $abc->getStorage();
        $http = $abc->sharedService(\ABC\ABC::HTTP);
        $this->request  = $http->createRequest();
        $this->response = $http->createResponse();
     
        if (isset($_POST['_method']) && isset(self::$validMethods[$_POST['_method']])) {
            $this->request = $this->request->withMethod($_POST['_method']);
        }
        
        $this->method = $this->request->getMethod();
    }     

    /**
    *
    *
    * @param string $pattern
    * @param callable $callable
    *
    * @return object
    */ 
    public function get($pattern = null, $callable = null)
    {
        if ($this->method !== 'GET') {
            return false;
        }
        
        $this->resolver($pattern, $callable);
        return $this;
    }
    
    /**
    *
    *
    * @param string $pattern
    * @param callable $callable
    *
    * @return object
    */ 
    public function post($pattern = null, $callable = null)
    {
        if ($this->method !== 'POST') {
            return false;
        }
     
        $this->resolver($pattern, $callable);
        return $this;
    }
    
    /**
    *
    *
    * @param string $pattern
    * @param callable $callable
    *
    * @return object
    */ 
    public function put($pattern = null, $callable = null)
    {
        if ($this->method !== 'PUT') {
            return false;
        }
        
        $this->resolver($pattern, $callable);
        return $this;
    }
    
    /**
    *
    *
    * @param string $pattern
    * @param callable $callable
    *
    * @return object
    */ 
    public function delete($pattern = null, $callable = null)
    {
        if ($this->method !== 'DELETE') {
            return false;
        }
        
        $this->resolver($pattern, $callable);
        return $this;
    }
    
    /**
    *
    *
    * @param array|string $methods
    * @param string $pattern
    * @param callable $callable
    *
    * @return object
    */ 
    public function any($methods = [], $pattern = null, $callable = null)
    {
        $methods = is_string($methods) ? [$methods] : $methods;
        
        foreach ($methods as $method) {
            if ($this->method === $method) {
                $this->resolver($pattern, $callable);
            }
        }
        
        return $this;        
    }
    
    /**
    *
    *
    * @param string $pattern
    * @param callable $callable
    *
    * @return object
    */ 
    public function all($pattern = null, $callable = null)
    {
        if (isset(self::$validMethods[$this->method])) {
            $this->resolver($pattern, $callable);        
        }
     
        return $this;
    }
    
    /**
    *
    *
    * @param string $pattern
    * @param callable $callable
    *
    * @return object
    */ 
    protected function resolver($pattern = null, $callable = null)
    {
        $path = $this->request->getUri()->getPath();
        $path = '/'. trim($path, '/') .'/';
     
        if (!$this->resolve($pattern, $path)) {
            return false;
        }
        
        if (is_string($callable) && false !== strpos($callable, '@')) {
            $this->appManager($path, $callable);
            return false;
        }
     
        if (is_string($callable) && class_exists($callable)) {
         
            if (!method_exists($callable, '__invoke')) {
                AbcError::badFunctionCall(ABC_NO_INVOKE);
                return false;
            }
            
            $callable = new $callable;
        }
        
        if (!is_callable($callable)) {
            AbcError::badFunctionCall(ABC_NO_CALLBACK);
            return false;
        } 
        
        $GET = $this->setParameters($path);
        $this->request = $this->request->withAttributes($GET);
        
        $response = call_user_func_array($callable, 
                           [$this->request, $this->response]
        );
        
        $this->storage->add(\ABC\ABC::RESPONSE, $response);
    } 
    
    /**
    *
    *
    * @param string $path
    * @param string $rout
    *
    */ 
    protected function appManager($path, $rout)
    {
        $routes = explode('@', $rout);
        $GET = $this->setParameters($path, $routes);
        
        ob_start();
        (new AppManager($this->abc))->run();
        $content = ob_get_clean();
        
        $this->response->write($content);
        unset($content);
        $this->storage->add(\ABC\ABC::RESPONSE, $this->response);    
    }
}
