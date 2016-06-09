<?php 

namespace ABC\Abc\Components\Tree;

/**  
* Класс формирования древовидного вывода результата 
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  

class Tree 
{ 

    protected $shift;
    protected $shiftCcnt = 0;
    protected $maxNest; 
    protected $result = ''; 

    public function __construct($abc) {}
    
    /** 
    * Установка данных 
    * 
    * @param array $rows 
    *
    * @return void
    */ 
    public function setRows($rows) 
    {        
        $this->rows = $rows; 
    } 
    
    /** 
    * Метод формирования дерева 
    * 
    * @param int $shift //Сдвиг вправо в пикселях 
    * @param int $maxNest // Максимальная вложенность 
    *
    * @return string 
    */       
    public function createTree($shift = 40, $maxNest = 10) 
    { 
        $this->shift   = $shift; 
        $this->maxNest = $maxNest; 
      
        $data = $this->sortArray(); 
        $this->recursiveTree($data, $parent = 0, $shift = 0); 
        return $this->result; 
    }     

    /** 
    * Рекурсивный метод обхода массива 
    * 
    * @param array $data 
    * @param int $parent 
    * @param int $shift 
    *
    * @return void 
    */       
    protected function recursiveTree($data, $parent, $shift) 
    { 
        $arr   = $data[$parent]; 
        $cnt   = count($arr); 
        $style = ''; 
       
        if (!empty($shift) && ++$this->shiftCcnt < $this->maxNest) { 
            $style = ' style="padding-left:'. $shift .'px;"'; 
        }
        
        for ($i = 0; $i < $cnt; $i++) { 
         
            $this->result .= "<div". $style .">\n"; 
            $this->result .= $arr[$i]['rows']; 
         
            if (isset($data[$arr[$i]['id']])) { 
                $this->recursiveTree($data, $arr[$i]['id'], $this->shift); 
            }
            
            $this->result .= "</div>\n"; 
        } 
    }     

    /** 
    * Метод формирования массива дерева
    *
    * @return array 
    */       
    protected function sortArray() 
    { 
        $cnt = count($this->rows); 
      
        for ($i = 0; $i < $cnt; ++$i) {
            $arr[$this->rows[$i]['id_parent']][] = $this->rows[$i]; 
        }
        
        return $arr; 
    } 

} 