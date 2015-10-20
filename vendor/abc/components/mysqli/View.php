<?php

namespace ABC\abc\components\mysqli;

/** 
 * Класс View
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */  

class View
{
    /**
    * Помечает проблемный участок SQL
    *
    * @param 
    *
    * @return void
    */    
    public function highlightLocation($sql, $location)
    {
        return str_replace($location, '<b style="color:red">'. $location .'</b>', $sql);
    }

    /**
    * Генерирует листинг SQL
    *
    * @param 
    *
    * @return void
    */    
    public function createReport($data)
    { 
        $data['num'] = implode('<br>', $data['num']);
        $tpl = $this->getTpl(__DIR__ .'/tpl/report.tpl');
        $this->display($this->parseTpl($tpl, $data));
    }    
    
    /**
    * Генерирует EXPLAIN
    *
    * @param string $data
    *
    * @return void
    */     
    public function createExplain($data)
    { 
        $tpl = $this->getTpl(__DIR__ .'/tpl/explain.tpl');
        return $this->parseTpl($tpl, $data);
    }
    
    /**
    * Подсветка линии
    *
    * @return string
    */  
    public function wrapLine($line, $type)
    {
        return '<span class="abc_'. $type .'_line">'. $line .'</span>';
    } 
    
    /**
    * Подсветка php кода
    *
    * @param string $blockCont
    * @param int $position
    *
    * @return string
    */    
    public function highlightString($php, $position, $size)
    {
        $descr = preg_match('~^[\r\n\s\t]*?<\?php~uis', $php) ? '' : '<?php ';
        $php   = highlight_string($descr . $php, true);
        $lines = preg_split('~<br[\s/]*?>~ui', $php);       
        $lines = array_slice($lines, $position, $size);
        return implode('<br />', $lines);
    } 
    
    /**
    * Возвращает сформированный листинг php кода
    *
    * @param string $blockCont
    * @param int $position
    *
    * @return string
    */    
    public function createPhp($data)
    {
        $data['num'] = implode('<br>', $data['num']);
        $tpl = $this->getTpl(__DIR__ .'/tpl/php.tpl');
        return $this->parseTpl($tpl, $data);
    }     
    /**
    * Читает шаблон
    *
    * @param string $file
    *
    * @return string
    */       
    public function getTpl($file)
    {
        return file_get_contents($file);
    }
    
    /**
    * Заполняет шаблон данными и возвтащает его
    *
    * @param string $tpl
    * @param array $data
    *
    * @return string
    */     
    public function parseTpl($tpl, $data)
    {
        $tpl = preg_replace('#\{\$(.+?)\}#i', '<?=$\\1;?>', $tpl);
        extract($data);
        ob_start();
        eval('?>'. $tpl);
        return ob_get_clean();
    }
    
    
    /**
    * Выдает результат в поток
    *
    * @param string $html
    *
    * @return void
    */     
    public function display($html)
    {
        echo $html;
    }    
}









