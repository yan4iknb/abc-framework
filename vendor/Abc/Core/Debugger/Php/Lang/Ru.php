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
        return [ 'Undefined variable'        => 'Undefined variable <span style="color:#400080"><br />(Не определена переменная)</span>',
                 'Undefined offset'          => 'Undefined offset <span style="color:#400080"><br />(Не определено смещение (номер элемента массива))</span>',
                 'Undefined index'           => 'Undefined index <span style="color:#400080"><br />(Не определен индекс массива)</span>',
                 'Use of undefined constant (.+?) assumed (.+)' => 'Use of undefined constant <b>$1</b>  assumed <b>$2</b> <span style="color:#400080"><br />(Используется неопределенная константа <b>$1</b>, предполагается <b>$2</b>)</span>',
                 'Use of undefined constant' => 'Use of undefined constant <span style="color:#400080"><br />(Используется неопределенная константа)</span>',
                 '(.+?)expects parameter (\d+?) to be (.+?), (.+?) given' => '<b>$1</b> expects parameter $2 to be $3, $4 given <span style="color:#400080"><br />(<b>$1</b> ожидает, что $2-м параметром будет ᐃ$3, а используется ᐃ$4)</span>',
                 '(.+?): Empty delimiter' => '<b>$1</b>: Empty delimiter <span style="color:#400080"><br />(<b>$1</b>: отсутствует разделитель)</span>',
                 '(.+?)expects exactly (\d+?) parameters, (\d+?) given' => '<b>$1</b> expects exactly $2 parameters, $3 given <span style="color:#400080"><br />(<b>$1</b> ожидает  параметров: $2, а используется $3)</span>',
                 'Declaration of (.+?) should be compatible with (.+)' => 'Declaration of <b>$1</b> should be compatible with <b>$2</b> <br /><span style="color:#400080">(Задекларированный <b>$1</b> должен быть совместим с <b>$2</b>)</span>',
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

























