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
        * Общие настройки
        */ 
        define('ABC_INVALID_CONFIGURE_APP',    'Configuring the application is to be performed array (конфигурация приложения должна быть массивом)');
        define('ABC_INVALID_CONFIGURE_SITE',   'Configuring the site is to be performed array (конфигурация сайта должна быть массивом)');
        define('ABC_UNKNOWN_ROUTES',           'Unknown type of routing data (неизвестный тип маршрутизации)');
        /**
        * Настройки дебаггера
        */ 
        define('ABC_INVALID_DEBUG_SETTING',    ' Incorrect configuration debugging (некорректная настройка дебаггинга)');
        define('ABC_TRACING_VARIABLE',         ' Tracing Variable (трассировка переменной) ');
        define('ABC_TRACING_OBJECT',           ' Tracing Object (трассировка объекта) ');
        define('ABC_TRACING_CONTAINER',        ' Tracing Container (трассировка контейнера) ');        
        define('ABC_TRACING_CLASS',            ' Tracing Class (трассировка класса) ');    
        /**
        * Ошибки использования контейнера зависимостей
        */ 
        define('ABC_INVALID_SERVICE_NAME',     ' Service name should be a string (название сервиса должно быть строкой)');
        define('ABC_NO_SERVICE',               ' service is not defined (сервис не определен)');  
        define('ABC_ALREADY_SERVICE',          ' service is already installed (сервис уже имеется в хранилище)');       
        define('ABC_NOT_FOUND_SERVICE',        ' service not found (сервис не найден)'); 
        define('ABC_INVALID_CALLABLE',         ' Argument must be a function of anonymity is conferred (аргумент должен быть анонимной функцией)');        
        define('ABC_SYNTHETIC_SERVICE',        ' service created synthetically. Impossible to implement services according to the synthetic (сервис создан синтетически. Невозможно внедрение зависимости в синтетический сервис)');
        define('ABC_INVALID_PROPERTY',         ' Property should be a array (свойство должно быть массивом)');
        define('ABC_NOT_REGISTERED_SERVICE',   ' service is not registered in a container (сервис не зарегистрирован в хранилище)');
        /**
        * Ошибки использования компонентов СУБД
        */   
        define('ABC_WRONG_CONNECTION',         ' wrong data connection in the configuration file (неверные данные коннекта в конфигурационном файле)');
        define('ABC_NO_SQL_DEBUGGER',          'SQL debugger is inactive. Set to true debug configuration. (SQL дебаггер не установлен. Установите настройку в конфигурационном файле)');    
        define('ABC_INVALID_MYSQLI_TYPE',      'Number of elements in type definition string doesn\'t match number of bind variables  (количество элементов типа отличается от количества аргументов)');
        define('ABC_NO_MYSQLI_TYPE',           ' Unknown type of the parameter  (неизвестный тип параметра) ');
    }

}