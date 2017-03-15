<?php

namespace ABC\Abc\Components\Sql\SqlDebug\Lang;

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
    public static function setConstants()
    {
        define('ABC_COMMAND_SELECT',          ' Syntax error for the SELECT statement <br /><span style="color:red">(ошибка синтаксиса оператора SELECT)</span><br />');
        define('ABC_SQL_SEQUENCE',            ' Operator sequence error <br /><span style="color:red">(ошибка последовательности операторов)</span><br />');
        define('ABC_SQL_DUBLE',               ' Operator repeat <br /><span style="color:red">(повтор оператора)</span><br />');
        define('ABC_SQL_NO_CONDITIONS',       ' No conditions are specified <br /><span style="color:red">(не задано имя поля или значение)</span><br />');
        define('ABC_SQL_COUNT_VALUES',        ' Insufficient values for the operator <br /><span style="color:red">(недостаточно значений для оператора)</span><br />');
        define('ABC_SQL_INVALID_CONDITIONS',  ' Error in setting conditions <br /><span style="color:red">(ошибка в задании условий)</span><br />');
        define('ABC_SQL_INVALID_VALUES',      ' Error in setting values <br /><span style="color:red">(ошибка в задании значений)</span><br />');
        define('ABC_SQL_INVALID_OPERATOR',    ' Operator not supported <br /><span style="color:red">(оператор не поддерживается)</span><br />');  
    }


    protected static function errorReportings() 
    {
        return [
'Base Table or view not found: (\d*?)(.+)' => ' $2 ',        
'Table(.+)doesn\'t exist' => 'Table$1doesn\'t exist<br /><span class="translate">(Таблица<strong>$1</strong>не существует)</span><br />',
'You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near(.+)at line (.+)' => 'You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near$1at line $2<br /><span class="translate">(Ошибка SQL синтаксиса. Обратитесь к мануалу, соответствующему Вашей версии MySQL сервера, чтобы использовать верно строку$1на линии $2)</span><br />',
'Field \'(.+?)\' doesn\'t have a default value' => 'Field \'$1\' doesn\'t have a default value<br /><span class="translate">Поле <strong>\'$1\'</strong> не имеет значения по умолчанию</span><br />',

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
