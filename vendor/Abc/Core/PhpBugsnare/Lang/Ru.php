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
      
'(.*?)Unknown database(.+)' => 'Unknown database$2<br /><span class="translate">

(Неизвестная база данных$2)

</span><br />',
'syntax error, unexpected(.+)expecting(.+)or(.+)' => 'Synᐃtax error, unexpected$1expecting$2or$3<br /><span class="translate">

(Синтаксическая ошибка, неожиданное:<strong>$1</strong>, ожидалось <strong>$2</strong>ᐃ<strong>$3</strong>)

</span><br />',
'syntax error, unexpected (\'.+?\')' => 'Syntax error, unexpected $1<br /><span class="translate">

(Синтаксическая ошибка, неожиданное: <strong>$1</strong>)

</span><br />',        

'Undefined variable: (.+)' => 'Undefined variable: <strong>$$1</strong><br /><span class="translate">

(Не определена переменная: $<strong>$1</strong>)

</span><br />',
'Undefined property: (.+)' => 'Undefined property:<strong>$1</strong><br /><span class="translate">

(Не определено свойство)

</span><br />',
'Undefined offset: (.+)' => 'Undefined offset: <strong>$1</strong> <br /><span class="translate">

(Не определено смещение (номер элемента массива))

</span><br />',
'Undefined index: (.+)' => 'Undefined index: <strong>$1</strong><br /><span class="translate">

(Не определен индекс массива)

</span><br />',
'Use of undefined constant(.*)' => 'Use of undefined constant <br /><span class="translate">

(Используется неопределенная константа)

</span><br />',
'Constant (.+?) already defined' => 'Constant <strong>$1</strong> already defined<br /><span class="translate">

(Константа <strong>$1</strong> уже определена)

</span><br />',
'(.+?)expects parameter (\d+?) to be (.+?), (.+?) given' => '<strong>$1</strong> expects parameter $2 to be $3, $4 given <br /><span class="translate">

(<strong>$1</strong> ожидает, что $2-м параметром будет $3, а используется ᐃ$4)

</span><br />',
'(.+?): Empty delimiter' => '<strong>$1</strong>: Empty delimiter <br /><span class="translate">

(<strong>$1</strong>: отсутствует разделитель)

</span><br />',
'(.+?)expects exactly (\d+?) parameter[s]*, (\d+?) given' => '<strong>$1</strong> expects exactly $2 parameters, $3 given <br /><span class="translate">

(<strong>$1</strong> ожидает  параметров: $2, а используется $3)

</span><br />',
'Declaration of (.+?) should be compatible with (.+)' => 'Declaration of <strong>$1</strong> should be compatible with <strong>$2</strong> <br /><span class="translate">

(Задекларированный <strong>$1</strong> должен быть совместим с <strong>$2</strong>)

</span><br />',
'Missing argument (\d+?) for (.+?), called in (.+?) on line (\d+?) and defined' => 'Missing argument $1 for $2, called in $3 on line $4 and defined <br /><span class="translate">

(Отсутствует аргумент $1 для $2, вызванного из $3 на линии $4)

</span><br />',
'Invalid argument supplied for (.+)' => 'Invalid argument supplied for $1 <br /><span class="translate">

(Неверный аргумент передан в $1)

</span><br />',
'Division by zero' => 'Division by zero<br /><span class="translate">

(Деление на ноль)

</span><br />',
'Trying to get property of non-object' => 'Trying to get property of non-object<br /><span class="translate">

(Попытка получить свойство не из объекта)

</span><br />',
'Creating default object from empty value' => 'Creating default object from empty value<br /><span class="translate">

(Создание объекта из пустого значения)

</span><br />',
'Cannot modify header information - headers already sent by \(output started at(.+?)\)' => 'Cannot modify header information - headers already sent by (output started at $1)<br /><span class="translate">

(Не удается изменить информацию в заголовке - заголовки уже отправлены (отправка начата на $1))

</span><br />',
'Array to string conversion' => 'Array to string conversion<br /><span class="translate">

(Массив преобразуется в строку)

</span><br />',
'Call to a member function (.+?)on null'  => 'Call to a member function <strong>$1</strong> on null<br /><span class="translate">

(Вызов метода <strong>$1</strong> из NULL)

</span><br />',
'Call to undefined method (.+)'   => 'Call to undefined method: <strong>$1</strong><br /><span class="translate">

(Вызов неопределенного метода)

</span><br />',
'Call to a member function (.+?) on boolean' => 'Call to a member function <strong>$1</strong> on boolean<br /><span class="translate">

(Вызов метода <strong>$1</strong> из булева значения)

</span><br />',
'Parameter (.+?) to (.+?) expected to be a reference, value given' => 'Parameter $1 to <strong>$2</strong> expected to be a reference, value given<br /><span class="translate">

(Параметр $1 в <strong>$2</strong> ожидался ссылкой, а задан значением)

</span><br />',
'Cannot pass parameter (.+?) by reference' => 'Cannot pass parameter $1 by reference<br /><span class="translate">

(Невозможно передать параметр $1 по ссылке)

</span><br />',
'Call-time pass-by-reference has been removed' => 'Call-time pass-by-reference has been removed<br /><span class="translate">

(Время передачи по ссылке было удалено)

</span><br />',
'Method (.+?) cannot take arguments by reference' => 'Method <strong>$1</strong> cannot take arguments by reference<br /><span class="translate">

(Метод не может принимать аргументы по ссылке)

</span><br />',
'There is already an active transaction' => 'There is already an active transaction<br /><span class="translate">

(Уже есть активная транзакция)

</span><br />',
'There is no active transaction' => 'There is already an active transaction<br /><span class="translate">

(Нет активных транзакций)

</span><br />',
'Object of class (.+?) to string conversion' => 'Object of class $1 to string conversion<br /><span class="translate">

(Объект или класс <strong>$1</strong> преобразуется в строку)

</span><br />',
'Object of class (.+?) could not be converted to string' => 'Object of class <strong>$1</strong> could not be converted to string<br /><span class="translate">

(Объект или класс <strong>$1</strong> нельзя преобразовать в строку)

</span><br />',

'Call to protected method (.+?) from context (.+)' => 'Call to protected method <strong>$1</strong> from context <strong>$2</strong><br /><span class="translate">

(Вызов защищенного метода <strong>$1</strong> из контекста <strong>$2</strong>)

</span><br />',
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
