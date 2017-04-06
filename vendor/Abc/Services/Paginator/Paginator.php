<?php

namespace ABC\Abc\Services\Paginator;

use ABC\Abc\Core\Exception\AbcError;

/** 
 * Класс Paginator 
 * Пагинатор
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */ 
   
class Paginator
{
    /**
    * @var ABC\Abc\Components\UrlManager\UrlManager
    */
    protected $url;

    protected $startPage;
    protected $numPage; 
    protected $numRows;   
    protected $numColumns;
    protected $param;
    protected $total = null;
    
    /**
    * @param object $abc
    */    
    public function __construct($abc) 
    {
        $this->uri = $abc->newService('UriManager');
    }

    /**
    * Устанавливает значения для пагинации.
    *
    * @param int $page
    * @param int $rows 
    * @param int $columns 
    *
    * @return void
    */    
    public function setNums($page = 1, $rows = 1, $columns = 1) 
    {
        $this->numPage    = (int)$page;    
        $this->numRows    = (int)$rows;
        $this->numColumns = (int)$columns;
    } 
    
    /**
    * Генерирует положение сдвига.
    * 
    * @param int $count 
    *
    * @return string
    */    
    public function getOffset($count)
    { 
        $this->total = intval(($count - $this->numColumns) / $this->numRows * $this->numColumns) - 1;
     
        if ($this->numPage < 1) {
            $this->numPage = 1;
        }
        
        if (empty($this->total) || $this->total < $count) {
            $this->total = $count;
        }
        
        if ($this->numPage > $this->total) { 
            $this->numPage = $this->total; 
        }
        
        $this->startPage = $this->numPage * $this->numRows * $this->numColumns - $this->numRows * $this->numColumns;
     
        if ($this->startPage < 0) {
            $this->startPage = 0;
        }
        
        return  $this->startPage;
     
    } 
    
    /**
    * Генерирует лимит для SQL запроса.
    * 
    * @param int $count 
    *
    * @return string
    */    
    public function getLimit()
    { 
        return  $this->numRows * $this->numColumns;
    } 
    
    /**
    * Генерирует меню навигации
    * 
    * @param string $param
    *
    * @return string
    */    
    public function createMenu($param = 'num')
    { 
        if (is_null($this->total)) {
            AbcError::logic(ABC_NO_TOTAL);
        }
     
        $this->param = $param;
       
        $count = ceil($this->total / $this->numRows / $this->numColumns);
        $menu = "\n<!-- Paginator begin -->\n";
     
        if ($count < 13)  {          
            $i = 1;    
            $cnt = $count;
        } else {
         
            if ($this->numPage > 10) {
                $menu .= $this->createLink(($this->numPage - 10), '-10&lt;', 'top');
            }
            
            if ($count > 12) { 
             
                if ($this->numPage == 7) {
                    $menu .= $this->createLink(1, 1);
                }  elseif($this->numPage == 8) {
                    $menu .= $this->createLink(1, 1) 
                          .  $this->createLink(2, 2);
                } elseif($this->numPage > 7) {
                    $menu .= $this->createLink(1, 1) 
                          .  $this->createLink(2, 2) 
                          .  $this->createLink(0, '...', 'top', false);
                }
            }    
         
            if ($this->numPage < 6) {  
                $i = 1;
                $cnt = 10;
            } elseif($this->numPage >= $count) { 
                $i = $count - 10; 
                $cnt = $count; 
            } else {   
                $i = $this->numPage - 5;
                $cnt = $count;
            }
         
            if ($this->numPage < 6) { 
                $cnt = $i + 9;
            } elseif($count - $i > 10) {
                $cnt = $i + 10;
            }  
        }        
     
        while ($i <= $cnt) {
         
            if ($i == $this->numPage) {
                $menu .= $this->createLink($i, $i, 'active', false);
            } else {
                $menu .= $this->createLink($i, $i);
            }
            
            $i++;
        }  
       
        if ($count > 12)  { 
         
            if ($this->numPage < $count - 6) {
                $menu .= $this->createLink(0, '...', 'top', false)
                      .  $this->createLink(($count - 1), ($count - 1));
            }
            
            if ($this->numPage < $count - 5) {
                $menu .= $this->createLink($count, $count);
            }
        }
     
       $end = ($this->numPage  + 10 > $count) ? $count : $this->numPage + 10;
     
        if($this->numPage < $count - 5 && $count - $this->numPage >= 10) {
           $menu .= $this->createLink($end, '&gt;+10', 'top');
        }
        
        return $menu ."\n<!-- Paginator end -->\n";
    }

    /**
    * Возвращает подготовленную ссылку
    *
    * @param int $page
    * @param string $link 
    * @param string $class
    * @param bolean $active
    * 
    * @return string
    */      
    protected function createLink($page = 1, $num = null, $class = null, $active = true)
    {                   
        $num   = empty($link)  ? $page  : $num;
        $class = empty($class) ? 'link' : $class;
        $pattern = '<'. $this->param .':\d+>';
        
        if ($active) { 
            return '<span class=\"'. $class .'">'
                 . '<a href="'. $this->uri->addParamToUri($this->param, $num, $pattern) .'" >'. $num .'</a>'
                 . '</span>';
        }
        
        return '<span class="'. $class .'">'. $num .'</span>';
    }
}
