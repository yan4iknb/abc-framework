<?php

namespace ABC\Abc\Components\Converter;

use ABC\Abc\Core\Exception\AbcError;
use ABC\abc\Components\Converter\Yaml;

/** 
 * Converter
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class Converter 
{
    /**
     * @var string
     */
    public $language;
    
    /**
     * Конструктор
     *
     * @param $language
     * 
     */
    protected function __construct($language = null)
    {
        if (empty($language)) {
            AbcError::invalidArgument('Component Converter: no language settings');
        } else {
            $this->language = strtolower($language);
        }
    }

    /**
     * конвертируем XML документ(строку) в массив php
     *
     * @param $content
     *
     * @return array
     */
    public function parse($content)
    {
        switch ((string)$this->language) {
            case 'xml' :
                return $this->parseXml($content);
            case 'yaml' :
                return $this->parseYaml($content);
            case 'json' :
                return $this->parseJson($content);
            default :
                AbcError::invalidArgument('Component Converter: unknown language '. $content);
                return false;
        }
    }

    /**
     * конвертируем Yaml документ(строку) в массив php
     *
     * @param string $content
     *
     * @return mixed
     */
    protected function parseYaml($content)
    {
        $yaml = new Yaml();
        return $yaml->parser($content);
    }
    
    // Ну и так далее.
}