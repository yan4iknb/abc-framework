<?php

namespace ABC\Abc\Builders;

use ABC\Abc\Builders\AbcBuilder;

// Требующиеся дополнительные компоненты
use ABC\Abc\Components\TrainingExample\TrainingExample;

/** 
 * Класс MysqliBuilder
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  

class TraningBuilder extends AbcBuilder
{
    /**
    * @var array
    */ 
    protected $service = 'Training'; // <-- Это имя сервиса
    
    /**
    * Строит сервис.
    * 
    * @param bool $global
    *
    * @return void
    */ 
    protected function buildService($global = false)
    { // Формируем путь до компонента
        $component = '\ABC\Abc\Components\\'. $this->service .'\\'. $this->service; 
      // Получаем из конфиги необходимые настройки/ Ключ обязательно в нижнем регистре
        $data = @$this->config[strtolower($this->service)] ?: [];  
      // Здесь решаем, каким должен быть сервис. Глобальным (по приципу Singletone)
      // Или обычным, когда при каждом обращении формируются новые объекты
        $typeService = $global ? 'setGlobal' : 'set';
      // Помещаем сервис в локатор в виде анонимной функции
        $this->locator->$typeService(
            $this->service, // <--- Это название сервиса
            function() use ($component, $data) {// <--- Передаем в функцию имя компонента и настройки конфигурации
                // Подготавливаем к запуску интерфейс основного компонента
                $obj = new $component($data);
                // Тут добавляем к данным дополнительные объекты
                $obj->example = new TrainingExample;
                // Когда будет вызвана функция, будет создан и возвращен объект основного компонента
                // с переданными ему настройками и объектами других компонентов в виде зависимостей                
                return $obj;
            }
        );
    }   
}
