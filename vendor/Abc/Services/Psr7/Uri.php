<?php

namespace ABC\Abc\Services\Psr7;

use ABC\Abc\Core\Exception\AbcError;

/** 
 * Класс Uri
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/
 */   
class Uri
{
    const CHAR_UNRESERVED = 'a-zA-Z0-9_\-\.~';
    
    protected $storage;

    /**
    * Конструктор
    */ 
    public function __construct($abc, $uri = '')
    {
        $this->storage = $abc->newService('Storage');
     
        if (!is_string($uri)) {
            AbcError::InvalidArgument(ABC_URI_NO_STRING);
        } elseif (!empty($uri)) {
            $this->parseUri($uri);
        } else {
         
            $env = $abc->getConfig('environment');   
         
            $part['scheme']   = !empty($env['HTTPS']) ? 'https' : 'http';
            $part['username'] = !empty($env['PHP_AUTH_USER']);
            $part['password'] = !empty($env['PHP_AUTH_PW']);
         
            if (!empty($env['HTTP_HOST'])) {
                $part['host'] = $env['HTTP_HOST'];
            } else {
                $part['host'] = $env['SERVER_NAME'];
            }
         
            $part['port'] = !empty($env['SERVER_PORT']) ? (int)$env['SERVER_PORT'] : 80;
            
            if (preg_match('/^(\[[a-fA-F0-9:.]+\])(:\d+)?\z/', $part['host'], $matches)) {
                $part['host'] = $matches[1];
             
                if ($matches[2]) {
                    $part['port'] = (int)substr($matches[2], 1);
                }
                
            } else {
                $pos = strpos($part['host'], ':');
                if ($pos !== false) {
                    $part['port'] = (int)substr($part['host'], $pos + 1);
                    $part['host'] = strstr($part['host'], ':', true);
                }
            }
         
            $scriptName = parse_url($env['SCRIPT_NAME'], PHP_URL_PATH);
            $scriptDir = dirname($scriptName);
         
            $requestUri = parse_url('http://example.com'. $env['REQUEST_URI'], PHP_URL_PATH);
         
            $part['path'] = $part['basePath'] = '';
         
            if (stripos($requestUri, $scriptName) === 0) {
                $part['basePath'] = $scriptName;
            } elseif ($scriptDir !== '/' && stripos($requestUri, $scriptDir) === 0) {
                $part['basePath'] = $scriptDir;
            }
         
            if (!empty($part['basePath'])) {
                $part['path'] = ltrim(substr($requestUri, strlen($basePath)), '/');
            }
          
            $part['query'] = $env['QUERY_STRING'];
            if (null === $part['query']) {
                $part['query'] = parse_url('http://example.com'. $env['REQUEST_URI'], PHP_URL_QUERY);
            }
         
            $part['fragment'] = '';
            $this->storage->delete('env');
            $this->storage->addArray($part);
        }
    }
    
    /**
    * Инициализация при клонировании
    */ 
    public function __clone() 
    {
        $this->storage = clone $this->storage;
    }

    /**
    * Формирует Uri
    *
    * @return string
    */
    public function __toString()
    {
        $all = $this->storage->all();
        extract($all);
        $authority = $this->getUserInfo();
        $basePath = !empty($basePath) ? $basePath : $host;
     
        return (!empty($scheme)    ? $scheme .':' : '')
             . (!empty($authority) ? '//'. $authority : '//')
             . $basePath . '/' . ltrim($path, '/')
             . (!empty($query)     ? '?'. $query : '')
             . (!empty($fragment)  ? '#'. $fragment : '');
    } 
    
    /**
    * Возвращает схему URI.
    *
    * @return string
    */
    public function getScheme()
    {
        return $this->storage->get('scheme');
    }
    
    /**
    * Генерирует компонент пользователя для URI.
    *
    * @return string 
    */
    public function getAuthority()
    {
        $userInfo = $this->getUserInfo();
        $host = $this->getHost();
        $port = $this->getPort();
        return (!empty($userInfo) ? $userInfo .'@' : '') . $host . (null !== $port ? ':'. $port : '');
    }
    
    /**
    *  Возвращает данные пользователя
    *
    * @return string
    */
    public function getUserInfo()
    {
        $password = $this->storage->get('password');
        return $this->storage->get('user') . (!empty($password) ? ':'. $password : '');
    }
    
    /**
    *  Возвращает хост
    *
    * @return string 
    */
    public function getHost()
    {
        return $this->storage->get('host');
    }
    
    /**
    *  Возвращает порт
    *
    * @return null|int 
    */
    public function getPort()
    {
        return $this->storage->get('port') && !$this->hasStandardPort() ? $this->port : null;
    }
    
    /**
    *  Возвращает путь
    *
    * @return string 
    */
    public function getPath()
    {
        return $this->storage->get('path');
    }
    
    /**
    *  Возвращает строку запроса
    *
    * @return string 
    */
    public function getQuery()
    {
        return $this->storage->get('query');
    }
    
    /**
    * Возвращает фрагмент URI
    *
    * @return string
    */
    public function getFragment()
    {
        return $this->storage->get('fragment');
    }
    
    /**
    * Возвращает новый объект с новой схемой
    *
    * @param string $scheme 
    * @return static 
    */
    public function withScheme($scheme)
    {
        $this->storage->add('scheme', $this->filterScheme($scheme));
        return clone $this;
    }
    
    /**
    * Возвращает новый объект с новыми данными пользоваеля
    *
    * @param string $user 
    * @param null|string $password 
    * @return static 
    */
    public function withUserInfo($user, $password = null)
    {
        $this->storage->add('user', $user);
        $this->storage->add('password', ($password ? $password : ''));
        return clone $this;
    }

    /**
    * Возвращает новый объект с новым хостом
    *
    * @param string $host 
    * @return static 
    */
    public function withHost($host)
    {
        $this->storage->add('host', $host);
        return clone $this;
    }

    /**
    * Возвращает новый объект с новым портом
    *
    * @param null|int $port 
    * @return static 
    */
    public function withPort($port)
    {
        $this->storage->add('port', $this->filterPort($port));
        return clone $this;
    }

    /**
    * Возвращает новый объект с новым путем
    *
    * @param string $path 
    * @return static 
    */
    public function withPath($path)
    {
        if (!is_string($path)) {
            AbcError::InvalidArgument(ABC_PATH_NO_STRING);
            return false;
        }
     
        $path = $this->filterQuery($path);
     
        if (substr($path, 0, 1) == '/') {
            $path = '';
        }
        
        $this->storage->add('path', $path);
        return clone $this;
    }

    /**
    * Возвращает новый объект с новой строкой запроса
    *
    * @param string $query 
    * @return static 
    */
    public function withQuery($query)
    {
        if (!is_string($query) && !method_exists($query, '__toString')) {
            AbcError::InvalidArgument(ABC_URI_NO_STRING);
            return false;
        }
        
        if (false !== strpos($query, '#')) {
            AbcError::InvalidArgument(ABC_URI_IS_FRAGMENT);
            return false;
        }
        
        $query = ltrim((string)$query, '?');
        $this->storage->add('query', $this->filterQuery($query));
        return clone $this;
    }  

    /**
    * Возвращает новый объект с добавленным фрагментом
    *
    * @param string $fragment 
    *
    * @return static 
    */
    public function withFragment($fragment)
    {
        if (!is_string($fragment) && !method_exists($fragment, '__toString')) {
            AbcError::InvalidArgument(ABC_FRAGMENT_NO_STRING);
            return false;
        }
        
        $fragment = ltrim((string)$fragment, '#');
        $this->storage->add('fragment', $this->filterQuery($fragment));
        return clone $this;
    }
    
/*-----------------------------------------------------    
        Хэлперы
-------------------------------------------------------*/
    /**
     * Parse a URI into its parts, and set the properties
     */
    protected function parseUri($uri)
    {
        $parts = parse_url($uri);
     
        if (false === $parts) {
            AbcError::InvalidArgument(ABC_URI_IS_FRAGMENT);
            return false;
        }
     
        $parts['scheme']    = isset($parts['scheme'])   ? $this->filterScheme($parts['scheme']) : '';
        $parts['userInfo']  = isset($parts['user'])     ? $parts['user']     : '';
        $parts['host']      = isset($parts['host'])     ? $parts['host']     : '';
        $parts['port']      = isset($parts['port'])     ? $parts['port']     : null;
        $parts['path']      = isset($parts['path'])     ? $this->filterPath($parts['path']) : '';
        $parts['query']     = isset($parts['query'])    ? $this->filterQuery($parts['query']) : '';
        $parts['fragment']  = isset($parts['fragment']) ? $this->filterQuery($parts['fragment']) : '';
     
        if (isset($parts['pass'])) {
            $parts['userInfo'] .= ':' . $parts['pass'];
        }
        
        $this->storage->addArray($parts);
    }

    /**
     * Filter Uri scheme.
     *
     * @param  string $scheme Raw Uri scheme.
     * @return string
     *
     * @throws InvalidArgumentException If the Uri scheme is not a string.
     * @throws InvalidArgumentException If Uri scheme is not "", "https", or "http".
     */
    protected function filterScheme($scheme)
    {
        static $valid = [
            '' => true,
            'https' => true,
            'http' => true,
        ];

        if (!is_string($scheme) && !method_exists($scheme, '__toString')) {
            AbcError::InvalidArgument(ABC_SCHEME_NO_STRING);
            return false;
        }

        $scheme = str_replace('://', '', strtolower((string)$scheme));
        if (!isset($valid[$scheme])) {
            AbcError::InvalidArgument(ABC_INVALID_SCHEME);
            return false;
        }

        return $scheme;
    }

    /**
     * Фильтрует запрос или фрагмент
     *
     * @param string $query 
     * @return string 
     */
    protected function filterQuery($query)
    {
        return preg_replace_callback(
            '/(?:[^a-zA-Z0-9_\-\.~!\$&\'\(\)\*\+,;=%:@\/\?]+|%(?![A-Fa-f0-9]{2}))/',
            function ($match) {
                return rawurlencode($match[0]);
            },
            $query
        );
    }
    
    /**
    * Фильтрует path
    *
    * @param string $path
    *
    * @return string
    */
    private function filterPath($path)
    {
        return preg_replace_callback(
            '/(?:[^' . self::CHAR_UNRESERVED . ':@&=\+\$,\/;%]+|%(?![A-Fa-f0-9]{2}))/',
            function ($match) {
                return rawurlencode($match[0]);
            },
            $path
        );
    }

}

