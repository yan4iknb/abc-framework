<?php

namespace ABC\abc\components\debuger\php;

/** 
 * Класс PhpTemplates
 * Подготавливает HTML для вывода отчета дебаггера
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 * @package system.cmponents.debugger  
 */   

class PhpTemplates
{

 /**
 * Возвращает HTML отчета об ошибках
 *
 * @return string
 */ 
    public function displayReport($data)
    {
        $tpl = $this->getTpl(__DIR__ .'/tpl/report.tpl');
        echo $this->parseTpl($tpl, $data);
    }  
    
 /**
 * Возвращает HTML трассировки
 *
 * @return string
 */ 
    public function createTrace($data)
    {
        $tpl = $this->getTpl(__DIR__ .'/tpl/trace.tpl');
        return $this->parseTpl($tpl, $data);
    } 
    
 /**
 * Возвращает HTML ряда трассировки
 *
 * @return string
 */     
    public function getTraceRow()
    {
        return $this->getTpl(__DIR__ .'/tpl/trace_row.tpl');
    } 
    
 /**
 * Возвращает HTML блока с кодом
 *
 * @return string
 */  
    public function createBlock($data)
    {
        $tpl = $this->getTpl(__DIR__ .'/tpl/block.tpl');
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
 * Подсветка результата var_export
 *
 * @param string $code
 *
 * @return string
 */    
    public function highlightVarExport($code)
    {
        preg_match_all('~"(.*?)"[\]\n]~is', $code, $out);
        $strings = ['empty'  => '<span class="empty">empty</span>',
                    'array'  => '<span class="type">array</span>',
                    'object' => '<span class="type">object</span>',
                    'string' => '<span class="type">string</span>',
                    'int'    => '<span class="type">int</span>',
                         
        ];
     
        $code = str_replace(array_keys($strings), array_values($strings), $code);
        return $code;
    }     
   
 /**
 * Подсветка php кода
 *
 * @param string $code
 * @param int $position
 *
 * @return string
 */    
    public function highlightString($code, $position, $size)
    {
        $descr = preg_match('~^[\r\n\s\t]*?<\?php~uis', $code) ? '' : '<?php ';
        $code  = highlight_string($descr . $code, true);
        $lines = preg_split('~<br[\s/]*?>~ui', $code);       
        $lines = array_slice($lines, $position, $size);
        return implode('<br />', $lines);
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
        extract($data);
        ob_start();
        eval('?>'. $tpl);
        return ob_get_clean();
    }
}












































