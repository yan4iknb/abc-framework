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
    protected static function errorReportings() 
    {
        return [
'Base Table or view not found: (\d*?)(.+)' => ' $2 ',        
'Table(.+)doesn\'t exist' => 'Table$1doesn\'t exist<br /><span class="translate">(Таблица<strong>$1</strong>не существует)</span><br />',
'You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near(.+)at line (.+)' => 'You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near$1at line $2<br /><span class="translate">(Ошибка SQL синтаксиса. Обратитесь к мануалу, соответствующему Вашей версии MySQL сервера, чтобы использовать верно строку$1на линии $2)</span><br />',

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
