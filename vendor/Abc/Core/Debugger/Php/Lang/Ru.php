<?php

namespace ABC\Abc\Core\Debugger\Php\Lang;

/** 
 * Класс En
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class Ru
{
    protected static function errorReportings() 
    {
        return [ 'Undefined variable'        => 'Undefined variable (не определена переменная)',
                 'Undefined offset'          => 'Undefined offset (не определено смещение (номер элемента массива))',
                 'Undefined index'           => 'Undefined index (не определен индекс массива)',
                 'Use of undefined constant (.+?) assumed (.+)' => 'Use of undefined constant <b>$1</b>  assumed <b>$2</b> (используется неопределенная константа <b>$1</b>, предполагается <b>$2</b>)',
                 'Use of undefined constant' => 'Use of undefined constant (используется неопределенная константа)',
                 '(.+?)expects parameter (\d+?) to be (.+?), (.+?) given' => '<b>$1</b> expects parameter $2 to be $3, $4 given (<b>$1</b> ожидает, что $2-м параметром будет ᐃ$3, а используется ᐃ$4)',
                 '(.+?): Empty delimiter' => '<b>$1</b>: Empty delimiter (<b>$1</b>: отсутствует разделитель)',
                 '(.+?)expects exactly (\d+?) parameters, (\d+?) given' => '<b>$1</b> expects exactly $2 parameters, $3 given (<b>$1</b> ожидает  параметров: $2, а используется $3)',
                 //'' => '',
                 //'' => '',
                 //'' => '',
                 //'' => '',
                 'ᐃarray'  => 'массив',
                 'ᐃstring' => 'строка',
                 'ᐃobject' => 'объект',
        ];
    }

    public static function translate($message) 
    {
        $reporting = self::errorReportings();
        $patterns = [];
     
        foreach ($reporting as $key => $value) {
            $patterns[] = '#'. $key .'#iu';
        }
        return preg_replace($patterns, array_values($reporting), $message);
    }

}

























