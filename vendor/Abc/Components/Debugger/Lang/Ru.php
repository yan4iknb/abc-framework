<?php

namespace ABC\Abc\Components\Debugger\Lang;

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
        return [
'syntax error, unexpected(.+)expecting(.+)or(.+)' => 'Synᐃtax error, unexpected$1expecting$2or$3<br /><span class="translate">(Синтаксическая ошибка, неожиденное:<b>$1</b>, ожидалось <b>$2</b>ᐃ<b>$3</b>)</span><br />',
'syntax error, unexpected (\'.+?\')' => 'Syntax error, unexpected $1<br /><span class="translate">(Синтаксическая ошибка, неожиденное: <b>$1</b>)</span><br />',        

'Undefined variable: (.+)' => 'Undefined variable: <b>$$1</b><br /><span class="translate">(Не определена переменная: $<b>$1</b>)</span><br />',
'Undefined property: (.+)' => 'Undefined property:<b>$1</b><br /><span class="translate">(Не определено свойство)</span><br />',
'Undefined offset' => 'Undefined offset <br /><span class="translate">(Не определено смещение (номер элемента массива))</span><br />',
'Undefined index' => 'Undefined index <br /><span class="translate">(Не определен индекс массива)</span>',
'Use of undefined constant(.*)' => 'Use of undefined constant <br /><span class="translate">(Используется неопределенная константа)</span><br />',
'(.+?)expects parameter (\d+?) to be (.+?), (.+?) given' => '<b>$1</b> expects parameter $2 to be $3, $4 given <br /><span class="translate">(<b>$1</b> ожидает, что $2-м параметром будет ᐃ$3, а используется ᐃ$4)</span><br />',
'(.+?): Empty delimiter' => '<b>$1</b>: Empty delimiter <br /><span class="translate">(<b>$1</b>: отсутствует разделитель)</span><br />',
'(.+?)expects exactly (\d+?) parameters, (\d+?) given' => '<b>$1</b> expects exactly $2 parameters, $3 given <br /><span class="translate">(<b>$1</b> ожидает  параметров: $2, а используется $3)</span><br />',
'Declaration of (.+?) should be compatible with (.+)' => 'Declaration of <b>$1</b> should be compatible with <b>$2</b> <br /><span class="translate">(Задекларированный <b>$1</b> должен быть совместим с <b>$2</b>)</span><br />',
'Missing argument (\d+?) for (.+?), called in (.+?) on line (\d+?) and defined' => 'Missing argument $1 for $2, called in $3 on line $4 and defined <br /><span class="translate">(Отсутствует аргумент $1 для $2, вызванного из $3 на линии $4)</span><br />',
'Invalid argument supplied for (.+)' => 'Invalid argument supplied for $1 <br /><span class="translate">(Неверный аргумент передан в $1)</span><br />',
'Division by zero' => 'Division by zero<br /><span class="translate">(Деление на ноль)</span><br />',
                 //''  => '',
                 'Synᐃtax'  => 'Syntax',
                 'ᐃboolean' => 'boolean',
                 'ᐃnull'    => 'null',
                 'ᐃarray'   => 'массив',
                 'ᐃstring'  => 'строка',
                 'ᐃobject'  => 'объект',
                 'ᐃ'        => 'или'
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

    protected static function errorReportingsSql() 
    {
        return [
'Table(.+)doesn\'t exist' => 'Table$1doesn\'t exist<br /><span class="translate">(Таблица$1не существует)</span><br />',
'You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near(.+)at line (.+)' => 'You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near$1at line $2<br /><span class="translate">(Ошибка SQL синтаксиса. Обратитесь к мануалу, соответствующему Вашей версии MySQL сервера, чтобы использовать верно строку$1на линии $2)</span><br />'
        ];
    }

    public static function translateSql($message) 
    {
        $reporting = self::errorReportingsSql();
        $patterns = [];
     
        foreach ($reporting as $key => $value) {
            $patterns[] = '#'. $key .'#iu';
        }
        return preg_replace($patterns, array_values($reporting), $message);
    }    
    
}

























