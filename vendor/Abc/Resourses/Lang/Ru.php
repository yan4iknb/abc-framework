<?php

namespace ABC\Abc\Resourses\Lang;

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
    /**
    * Устанавливает языковые константы
    */     
    public static function set() 
    {
        /**
        * General constants
        */ 
        define('ABC_NO_CLASS',     ' class not found <br /><span style="color:#400080">(класс не найден)</span><br />');
        define('ABC_NO_METHOD',    ' method offline in framework <br /><span style="color:#400080">(метод не поддержиается фреймворком)</span><br />');
        define('ABC_TPL_DISABLE',  ' the template disabled <br /><span style="color:#400080">(шаблонизатор отключен)</span><br />');
        
        /**
        * Общие настройки
        */ 
        define('ABC_INVALID_CONFIGURE_APP',    ' Configuring the application is to be performed array <br /><span style="color:#400080">(конфигурация приложения должна быть массивом)</span><br />');
        define('ABC_INVALID_CONFIGURE_SITE',   ' Configuring the site is to be performed array <br /><span style="color:#400080">(конфигурация сайта должна быть массивом)</span><br />');
        define('ABC_UNKNOWN_ROUTES',           ' Unknown type of routing data <br /><span style="color:#400080">(неизвестный тип маршрутизации)</span><br />');
        define('ABC_ERROR_ROUTES_RULE',           ' Error in the routing rules <br /><span style="color:#400080">(ошибка в правилах роутинга)</span><br />');
        
        /**
        * Настройки дебаггера
        */ 
        define('ABC_INVALID_DEBUG_SETTING',    ' Incorrect configuration debugging <br /><span style="color:#400080">(некорректная настройка дебаггинга)</span><br />');
        define('ABC_TRACING_VARIABLE',         ' Tracing Variable <br /><span style="color:#400080">(трассировка переменной)</span><br />');
        define('ABC_TRACING_OBJECT',           ' Tracing Object <br /><span style="color:#400080">(трассировка объекта)</span><br />');
        define('ABC_TRACING_CONTAINER',        ' Tracing Container <br /><span style="color:#400080">(трассировка контейнера)</span><br />');        
        define('ABC_TRACING_CLASS',            ' Tracing Class <br /><span style="color:#400080">(трассировка класса)</span><br />');
        
        /**
        * Ошибки использования контейнера зависимостей
        */ 
        define('ABC_INVALID_SERVICE_NAME',     ' Service name should be a string <br /><span style="color:#400080">(название сервиса должно быть строкой)</span><br />');
        define('ABC_NO_SERVICE',               ' service is not defined <br /><span style="color:#400080">(сервис не определен)</span><br />');  
        define('ABC_ALREADY_SERVICE',          ' service is already installed <br /><span style="color:#400080">(сервис уже имеется в хранилище)</span><br />');       
        define('ABC_NOT_FOUND_SERVICE',        ' service not found <br /><span style="color:#400080">(сервис не найден)</span><br />'); 
        define('ABC_INVALID_CALLABLE',         ' Argument must be a function of anonymity is conferred <br /><span style="color:#400080">(аргумент должен быть анонимной функцией)</span><br />');        
        define('ABC_SYNTHETIC_SERVICE',        ' service created synthetically. Impossible to implement services according to the synthetic <br /><span style="color:#400080">(сервис создан синтетически. Невозможно внедрение зависимости в синтетический сервис)</span><br />');
        define('ABC_INVALID_PROPERTY',         ' Property should be a array <br /><span style="color:#400080">(свойство должно быть массивом)</span><br />');
        define('ABC_NOT_REGISTERED_SERVICE',   ' service is not registered in a container <br /><span style="color:#400080">(сервис не зарегистрирован в хранилище)</span><br />');
        
        /**
        * Ошибки использования компонентов СУБД
        */   
        define('ABC_WRONG_CONNECTION',         ' wrong data connection in the configuration file <br /><span style="color:#400080">(неверные данные коннекта в конфигурационном файле)</span><br />');
        define('ABC_NO_SQL_DEBUGGER',          ' SQL debugger is inactive. Set to true debug configuration. <br /><span style="color:#400080">(SQL дебаггер не установлен. Установите настройку в конфигурационном файле)</span><br />');    
        define('ABC_INVALID_MYSQLI_TYPE',      ' Number of elements in type definition string doesn\'t match number of bind variables  <br /><span style="color:#400080">(количество элементов типа отличается от количества аргументов)</span><br />');
        define('ABC_NO_MYSQLI_TYPE',           ' Unknown type of the parameter  <br /><span style="color:#400080">(неизвестный тип параметра)</span><br />');
        
        /**
        * Ошибки использования шаблонизатора
        */ 
        define('ABC_NO_TEMPLATE',              ' templates file  does not exist <br /><span style="color:#400080">(файл шаблона отутствует)</span><br />');
        define('ABC_INVALID_BLOCK',            ' block does not exist or incorrect syntax <br /><span style="color:#400080">(блок отсутствует, либо имеет некорректный синтаксис)</span><br />');
        define('ABC_NO_METHOD_IN_TPL',         ' templating method is not supported <br /><span style="color:#400080">(метод не поддерживается шаблонизатором)</span><br />');
        
        define('ABC_NO_MODEL',                 ' model is not implemented <br /><span style="color:#400080">(модель не реализована)</span><br />');
    }

}