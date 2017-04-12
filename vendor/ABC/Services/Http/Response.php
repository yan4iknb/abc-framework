<?php

namespace ABC\ABC\Services\Http;

use ABC\ABC\Core\Exception\AbcError;

/** 
 * Класс Response 
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author irbis-team.ru
 * @copyright © 2017
 * @license http://www.wtfpl.net/ 
 */   
class Response extends ResponseAddition
{
    use MessageTrait;
    
    protected $abc;
    protected static $messages = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        444 => 'Connection Closed Without Response',
        451 => 'Unavailable For Legal Reasons',
        499 => 'Client Closed Request',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        599 => 'Network Connect Timeout Error',
    ];    
    
    /**
    * Конструктор
    *
    * @param string|resource|object $stream 
    * @param int $status
    * @param array $headers
    */
    public function __construct($abc, $body = 'php://memory', $status = 200, array $headers = [])
    {
        $this->abc = $abc; 
     
        if (!is_string($body) && !is_resource($body) && !$body instanceof Stream) {
            AbcError::InvalidArgument(ABC_INVALID_STREAM);
        }
     
        if (null !== $status) {
            $this->validateStatus($status);
        }
        
        $this->initialize($headers);
        $body = ($body instanceof Stream) ? $body : new Stream($body, 'wb+');
        $this->storage->add('body', $body);
        $this->storage->add('status', $status ? (int) $status : 200);
    }
    
    /**
    * Инициализация при клонировании
    */ 
    public function __clone() 
    {
        $this->storage = clone $this->storage;
    }
    
    /**
    * Возвращает статус-код ответа.
    *
    * @return int.
    */
    public function getStatusCode()
    {
        return $this->storage->get('status');
    }

    /**
    * Возвращает новый объект с новым статус-кодом.
    *
    * @param int $code 
    * @param string $reasonPhrase 
    *
    * @return static
    */
    public function withStatus($code, $reasonPhrase = '')
    {
        $this->validateStatus($code);
        $this->storage->add('statusCode', (int)$code);
        $this->storage->add('reasonPhrase', $reasonPhrase);
        return clone $this;
    }

    /**
     * Получает причину статус-кода.
     *
     * @return string 
     */
    public function getReasonPhrase()
    {
        $statusCode = $this->storage->get('statusCode');
        
        if (!$this->storage->has('reasonPhrase')
            && isset(self::$messages[$statusCode])
        ) {
            $reasonPhrase = self::$messages[$statusCode];
            $this->storage->add('reasonPhrase', $reasonPhrase);
            return $reasonPhrase;            
        }
     
        return '';
    }
    
    /**
     * Проверяет статус-код.
     *
     * @param int|string $code
     */
    private function validateStatus($code)
    {
        if (!is_numeric($code)
            || is_float($code)
            || $code < 100
            || $code >= 600
        ) {
            AbcError::InvalidArgument(ABC_INVALID_STREAM);
            return false;
        }
    }
}
