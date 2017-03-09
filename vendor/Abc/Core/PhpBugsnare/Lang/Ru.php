<?php

namespace ABC\Abc\Core\PhpBugsnare\Lang;

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
'(.*?)Unknown database(.+)' => 'Unknown database$2<br /><span class="translate">(Неизвестная база данных$2)</span><br />',
'syntax error, unexpected(.+)expecting(.+)or(.+)' => 'Synᐃtax error, unexpected$1expecting$2or$3<br /><span class="translate">(Синтаксическая ошибка, неожиданное:<b>$1</b>, ожидалось <b>$2</b>ᐃ<b>$3</b>)</span><br />',
'syntax error, unexpected (\'.+?\')' => 'Syntax error, unexpected $1<br /><span class="translate">(Синтаксическая ошибка, неожиданное: <b>$1</b>)</span><br />',        

'Undefined variable: (.+)' => 'Undefined variable: <b>$$1</b><br /><span class="translate">(Не определена переменная: $<b>$1</b>)</span><br />',
'Undefined property: (.+)' => 'Undefined property:<b>$1</b><br /><span class="translate">(Не определено свойство)</span><br />',
'Undefined offset: (.+)' => 'Undefined offset: <strong>$1</strong> <br /><span class="translate">(Не определено смещение (номер элемента массива))</span><br />',
'Undefined index: (.+)' => 'Undefined index: <strong>$1</strong><br /><span class="translate">(Не определен индекс массива)</span><br />',
'Use of undefined constant(.*)' => 'Use of undefined constant <br /><span class="translate">(Используется неопределенная константа)</span><br />',
'(.+?)expects parameter (\d+?) to be (.+?), (.+?) given' => '<b>$1</b> expects parameter $2 to be $3, $4 given <br /><span class="translate">(<b>$1</b> ожидает, что $2-м параметром будет ᐃ$3, а используется ᐃ$4)</span><br />',
'(.+?): Empty delimiter' => '<b>$1</b>: Empty delimiter <br /><span class="translate">(<b>$1</b>: отсутствует разделитель)</span><br />',
'(.+?)expects exactly (\d+?) parameter[s]*, (\d+?) given' => '<b>$1</b> expects exactly $2 parameters, $3 given <br /><span class="translate">(<b>$1</b> ожидает  параметров: $2, а используется $3)</span><br />',
'Declaration of (.+?) should be compatible with (.+)' => 'Declaration of <b>$1</b> should be compatible with <b>$2</b> <br /><span class="translate">(Задекларированный <b>$1</b> должен быть совместим с <b>$2</b>)</span><br />',
'Missing argument (\d+?) for (.+?), called in (.+?) on line (\d+?) and defined' => 'Missing argument $1 for $2, called in $3 on line $4 and defined <br /><span class="translate">(Отсутствует аргумент $1 для $2, вызванного из $3 на линии $4)</span><br />',
'Invalid argument supplied for (.+)' => 'Invalid argument supplied for $1 <br /><span class="translate">(Неверный аргумент передан в $1)</span><br />',
'Division by zero' => 'Division by zero<br /><span class="translate">(Деление на ноль)</span><br />',
'Trying to get property of non-object' => 'Trying to get property of non-object<br /><span class="translate">(Попытка получить свойство не из объекта)</span><br />',
'Creating default object from empty value' => 'Creating default object from empty value<br /><span class="translate">(Создание объекта из пустого значения)</span><br />',
'Cannot modify header information - headers already sent by \(output started at(.+?)\)' => 'Cannot modify header information - headers already sent by (output started at $1)<br /><span class="translate">(Не удается изменить информацию в заголовке - заголовки уже отправлены (отправка начата на $1))</span><br />',
'Array to string conversion' => 'Array to string conversion<br /><span class="translate">(Массив преобразуется в строку)</span><br />',
'Call to a member function (.+?)on null'  => 'Call to a member function <strong>$1</strong> on null<br /><span class="translate">(Вызов метода <strong>$1</strong> из NULL)</span><br />',
'Call to undefined method (.+)'   => 'Call to undefined method: <strong>$1</strong><br /><span class="translate">(Вызов неопределенного метода)</span><br />',



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
}
