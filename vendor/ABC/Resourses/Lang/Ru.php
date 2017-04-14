<?php

namespace ABC\ABC\Resourses\Lang;

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
        * Общие константы
        */ 
        define('ABC_NO_SUPPORT_SERVICE',       ': service is not supported in the current configuration <br /><span style="color:red">(Сервис не поддерживается в текущей конфигурации)</span><br />');
        define('ABC_NO_FUNCTIONAL',            ' is not implemented <br /><span style="color:red">(Этот функционал не реализован)</span><br />');
        define('ABC_NO_CLASS',                 ' class not found <br /><span style="color:red">(класс не найден)</span><br />');
        define('ABC_NO_METHOD',                ' method offline in framework <br /><span style="color:red">(метод не поддержиается фреймворком)</span><br />');
        define('ABC_NO_CALLBACK',              ' Parameter must be a valid callback <br /><span style="color:red">(Аргументом должен быть валидный callback)</span><br />');
        define('ABC_NO_INVOKE',                ' The class must contain the <strong>__invoke()</strong> method<br /><span style="color:red">(Класс должен содержать метод <strong>__invoke()</strong>)</span><br />');  
        define('ABC_TPL_DISABLE',              ' the template disabled <br /><span style="color:red">(шаблонизатор отключен)</span><br />');
        define('ABC_INVALID_CONFIGURE_APP',    ' Configuring the application is to be performed array <br /><span style="color:red">(конфигурация приложения должна быть массивом)</span><br />');
        define('ABC_INVALID_CONFIGURE_SITE',   ' Configuring the site is to be performed array <br /><span style="color:red">(конфигурация сайта должна быть массивом)</span><br />');
        define('ABC_NO_CONFIGURE',             ' Setting is not specified in the configuration file <br /><span style="color:red">(настройка не задана в конфигурационном файле)</span><br />');
        define('ABC_INVALID_CONFIGURE',        ' Setup key must be a string <br /><span style="color:red">(ключ настройки должен быть строкой)</span><br />');        
        define('ABC_UNKNOWN_ROUTES',           ' Unknown type of routing data <br /><span style="color:red">(неизвестный тип маршрутизации)</span><br />');
        define('ABC_ERROR_ROUTES_RULE',        ' Error in the routing rules <br /><span style="color:red">(ошибка в правилах роутинга)</span><br />');
  
        /**
        * Request
        */        

        define('ABC_INVALID_STREAM',           'Invalid stream provided.<br /><span style="color:red">(Указан недопустимый поток)</span><br />');
        define('ABC_INVALID_PROTOCOL',         'Invalid HTTP version.<br /><span style="color:red">(Невалидная версия протокола HTTP)</span><br />');
        define('ABC_INVALID_TARGET',           'Invalid request target provided; cannot contain whitespace<br /><span style="color:red">(Некорректная цель запроса)</span><br />');        
        define('ABC_NO_HEADER',                ' - There is no such header. <br /><span style="color:red">(Нет такого заголовка.)</span><br />');
        define('ABC_VALUE_NO_STRING',          'Header must be a string or array of strings<br /><span style="color:red">(Заголовок должен быть строкой или массивом строк)</span><br />');    
        define('ABC_INVALID_HEADER_NAME',      'Invalid header name. <br /><span style="color:red">(Невалидное имя заголовка.)</span><br />');
        define('ABC_INVALID_HEADER_VALUE',     'Invalid header. <br /><span style="color:red">(Невалидный заголовок.)</span><br />');
        define('ABC_NO_RESOURCE',              ' is not a resource. <br /><span style="color:red">(Аргумент не является ресурсом.)</span><br />');
        define('ABC_NO_REWIND',                'Could not rewind stream<br /><span style="color:red">(Не удалось сбросить курсор потока)</span><br />');
        define('ABC_NO_POINTER',               'Could not get the position of the pointer in stream<br /><span style="color:red">(Не удалось получить позицию указателя в потоке)</span><br />'); 
        define('ABC_NO_WRITE',                 'Could not write to stream<br /><span style="color:red">(Не удалось запиисать в поток)</span><br />'); 
        define('ABC_NO_READ',                  'Could not read from stream<br /><span style="color:red">(Не удалось прочитать из потока)</span><br />');
        define('ABC_NO_CONTENT',               'Could not get contents of stream<br /><span style="color:red">(Не удалось пролучить контент из потока)</span><br />');
        define('ABC_PATH_NO_STRING',           'Path must be a string<br /><span style="color:red">(Path должен быть строкой)</span><br />');
        define('ABC_URI_NO_STRING',            'Uri must be a string<br /><span style="color:red">(URI должен быть строкой)</span><br />');
        define('ABC_INVALID_URI',              'The invalid Uri<br /><span style="color:red">(Невалидный Uri)</span><br />'); 
        define('ABC_SCHEME_NO_STRING',         'Uri scheme must be a string<br /><span style="color:red">(URI схема должна быть строкой)</span><br />');   
        define('ABC_INVALID_SCHEME',           'Uri scheme must be one of: "", "https", "http"<br /><span style="color:red">(URI схема должна быть одним из "", "https", "http")</span><br />'); 
        define('ABC_EMPTY_ARGYMENTS',          'Uri fragment must be a string<br /><span style="color:red">(Фрагмент должен быть строкой)</span><br />');   
        define('ABC_EMPTY_FILE_PATH',          'No path is specified for moving the file<br /><span style="color:red">(Не указан путь для перемещения файла)</span><br />'); 
        define('ABC_CANNOT_MOVE_FILE',         'Cannot move file<br /><span style="color:red">(Не удалось переместить файл)</span><br />'); 
        define('ABC_ERROR_MOVED',              'Cannot retrieve stream after it has already been moved <br /><span style="color:red">(Не удалось получить поток после его перемещения)</span><br />');
        define('ABC_ERROR_FILE',               'Error occurred while moving uploaded file <br /><span style="color:red">(Ошибка перемещения файла)</span><br />');
        define('ABC_URI_IS_FRAGMENT',          'Query string must not include a URI fragment<br /><span style="color:red">(Строка запроса не должна содержать #фрагмент)</span><br />');
        define('ABC_INVALID_STATUS',          'Invalid status code. Must be an integer between 100 and 599, inclusive<br /><span style="color:red">(Неверный статус-код. Код дожен быть в промежутке между 100 и 599)</span><br />');
        
        /**
        * Настройки дебаггера
        */ 
        define('ABC_TRACING_VARIABLE',         ' Tracing Variable <br /><span style="color:red">(трассировка переменной)</span><br />');
        define('ABC_TRACING_OBJECT',           ' Tracing Object <br /><span style="color:red">(трассировка объекта)</span><br />');
        define('ABC_TRACING_CONTAINER',        ' Tracing Container <br /><span style="color:red">(трассировка контейнера)</span><br />');        
        define('ABC_TRACING_CLASS',            ' Tracing Class <br /><span style="color:red">(трассировка класса)</span><br />');
        
        /**
        * Ошибки использования контейнера зависимостей
        */ 
        define('ABC_INVALID_SERVICE_NAME',     ' Service name should be a string <br /><span style="color:red">(название сервиса должно быть строкой)</span><br />');
        define('ABC_NO_SERVICE',               ' service is not defined <br /><span style="color:red">(сервис не определен)</span><br />');  
        define('ABC_ALREADY_SERVICE',          ' service is already installed <br /><span style="color:red">(сервис уже имеется в хранилище)</span><br />');       
        define('ABC_NOT_FOUND_SERVICE',        ' service not found <br /><span style="color:red">(сервис не найден)</span><br />'); 
        define('ABC_INVALID_CALLABLE',         ' Argument must be a function of anonymity is conferred <br /><span style="color:red">(аргумент должен быть анонимной функцией)</span><br />');        
        define('ABC_SYNTHETIC_SERVICE',        ' service created synthetically. Impossible to implement services according to the synthetic <br /><span style="color:red">(сервис создан синтетически. Невозможно внедрение зависимости в синтетический сервис)</span><br />');
        define('ABC_INVALID_PROPERTY',         ' Property should be a array <br /><span style="color:red">(свойство должно быть массивом)</span><br />');
        define('ABC_NOT_REGISTERED_SERVICE',   ' service is not registered in a container <br /><span style="color:red">(сервис не зарегистрирован в хранилище)</span><br />');
        
        /**
        * Ошибки использования компонентов СУБД
        */   
        define('ABC_WRONG_CONNECTION',         ' wrong data connection in the configuration file <br /><span style="color:red">(неверные данные коннекта в конфигурационном файле)</span><br />');
        define('ABC_NO_SQL_DEBUGGER',          ' SQL debugger is inactive. Set to true debug configuration. <br /><span style="color:red">(SQL дебаггер не установлен. Установите настройку в конфигурационном файле)</span><br />');    
        define('ABC_INVALID_MYSQLI_TYPE',      ' Number of elements in type definition string doesn\'t match number of bind variables  <br /><span style="color:red">(количество элементов типа отличается от количества аргументов)</span><br />');
        define('ABC_NO_MYSQLI_TYPE',           ' Unknown type of the parameter  <br /><span style="color:red">(неизвестный тип параметра)</span><br />');
        define('ABC_SQL_ERROR',                ' Query build error  <br /><span style="color:red">(Ошибка построения запроса)</span><br />');
        define('ABC_SQL_EMPTY_ARGUMENTS',      ' Not all arguments are specified in a member function <br /><span style="color:red"> Не все аргументы заданы в методе <strong>');        
        define('ABC_TRANSACTION_EXIST',        ' There is already an active transaction  <br /><span style="color:red">(Уже есть активная транзакция)</span><br />');
        define('ABC_TRANSACTION_ERROR',        ' Transaction error:  <br /><span style="color:red">Ошибка транзакции: </span><br />'); 
        define('ABC_NO_SUPPORT',               ' This type of table is not supported by the debugger  <br /><span style="color:red">(Этот тип таблицы не поддерживается дебаггером)</span><br />'); 
        define('ABC_OTHER_OBJECT',             ' An inappropriate object is used  <br /><span style="color:red">(Используется неподходящий объект)</span><br />'); 
        define('ABC_NO_METHOD_IN_DBC',         ' method is not supported by the Query builder<br /><span style="color:red">(метод не поддерживается конструктором запросов)</span><br />');
        define('ABC_ERROR_BINDVALUES',         ' The numbering of the array in the parameter of the <strong>bindValues()</strong> method must begin with 1<br /><span style="color:red">(Нумерация массива в параметре метода <strong>bindValues()</strong> должна начинаться с единицы)</span><br />');
        define('ABC_DBCOMAND_SERIALIZE',       ' You can not serialize a query builder object<br /><span style="color:red">(Нельзя сериализовать объект конструктора запросов)</span><br />');
        
        /**
        * Ошибки использования шаблонизатора
        */ 
        define('ABC_NO_TEMPLATE',              ' templates file  does not exist <br /><span style="color:red">(файл шаблона отутствует)</span><br />');
        define('ABC_INVALID_BLOCK',            ' block does not exist or incorrect syntax <br /><span style="color:red">(блок отсутствует, либо имеет некорректный синтаксис)</span><br />');
        define('ABC_NO_METHOD_IN_TPL',         ' templating method is not supported <br /><span style="color:red">(метод не поддерживается шаблонизатором)</span><br />');
        
        /**
        * Ошибки конфигурации
        */ 
        define('ABC_NO_MODEL',                 ' model is not implemented <br /><span style="color:red">(модель не реализована)</span><br />');
        
        /**
        * Ошибки использования пагинатора
        */         
        define('ABC_NO_TOTAL',                 ' limit is not set <br /><span style="color:red">(лимит не установлен)</span><br />');
    }
}