<?php

namespace ABC\abc\components;


class ComponentRegistry
{

    private $registry = [
                            'framework' => '1.0.0',
                            'debugger'  => '1.0.1',
            ];
    
 /**
 * Проверяет, установлен ли компонент.
 *
 * @param string $component
 *
 * @return bool
 */ 

    public function checkComponent($component)
    {
        return is_dir(__DIR__ .'/'. $this->registry);
    }
    
 /**
 * Возвращает версию компонента
 *
 * @param string $component
 *
 * @return string|bool
 */  
    public function getVersion($component = '')
    {
        if (empty($this->registry[$component])) {
            return $this->registry['framework'];
        }
        
        if (checkComponent($component)) {
            return $this->registry[$component];
        }
        
        return false;
    }
}











