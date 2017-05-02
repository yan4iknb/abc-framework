<?php

namespace ABC\ABC\Services\BbDecoder;


/** 
 * BB-декодер
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class BbDecoder 
{ 

    protected $bbOpen; 
    protected $bbClose;     
    protected $bbSingle;                                                        
    protected $htmlOpen; 
    protected $htmlClose; 
    protected $htmlSingle; 
    protected $tmpOpen; 
    protected $tmpClose; 
    protected $tmpSingle; 
    protected $maxLen; 
    protected $links; 
    protected $images; 
    protected $video; 

    public function __construct($abc) 
    {   
        $config = Setup::getConfig($abc); 
        extract($config);
     
        $this->bbOpen      = array_keys($setup_bb); 
        $this->bbClose     = array_values($setup_bb);                                                         
        $this->htmlOpen    = array_keys($setup_html); 
        $this->htmlClose   = array_values($setup_html); 
        $this->bbSingle    = array_keys($single_tags); 
        $this->htmlSingle  = array_values($single_tags); 
      
        $this->maxLen     = $max_len; 
        $this->links      = $links; 
        $this->images     = $images; 
        
        $tokens = Setup::getTokens(); 
        extract($tokens); 
        
        $this->tmpOpen    = $tmp_open; 
        $this->tmpClose   = $tmp_close; 
        $this->tmpSingle  = $tmp_single;  
    }
    
    /**    
    * Основной метод интерпретатора
    *
    * @param string $text
    *
    * @return string  
    */    
    public function convert($text) 
    {    
        $text = str_replace($this->tmpOpen, '', $text); 
        $text = str_replace($this->tmpClose, '', $text); 
        $text = str_replace($this->tmpSingle, '', $text); 
                    
        $text = str_replace("\r", "", $text); 
        $text = str_replace("\t", "    ", $text); 
         
        $text = str_ireplace($this->bbOpen, $this->tmpOpen, $text); 
        $text = str_ireplace($this->bbClose, $this->tmpClose, $text); 
        $text = str_ireplace($this->bbSingle, $this->tmpSingle, $text);   
      
        $openCnt = []; 
      
        foreach ($this->tmpOpen as $k => $v) {
         
            $text = preg_replace("~". preg_quote($v, '~') ."\s*?". preg_quote($this->tmpClose[$k], '~') ."~us", "", $text); 
            $cnt = substr_count($text, $v); 
            
            if ($cnt > 0) { 
               $openCnt[$v] = $cnt; 
               $closeCnt[$v] = substr_count($text, $this->tmpClose[$k]); 
            }               
        } 
      
        foreach ($openCnt as $k => $v) {
         
            if ($v > $closeCnt[$k]) { 
             
                for ($i = 0; $i < $v - $closeCnt[$k]; ++$i) { 
                    $text = preg_replace('~'. preg_quote($v, '~') .'(?!.*'. preg_quote($k, '~') .')~us', '', $text); 
                }
            }   
        }   
        
        $text = $this->mbWordwrap($text, $this->maxLen);                  
        $text = htmlspecialchars($text);  
        $text = $this->addLink($text); 
        $text = $this->addImg($text);
        
        $text = str_replace($this->tmpOpen, $this->htmlOpen, $text); 
        $text = str_replace($this->tmpClose, $this->htmlClose, $text); 
        $text = str_replace($this->tmpSingle, $this->htmlSingle, $text); 
      
        $text = str_replace('  ', '&nbsp;&nbsp;', $text);     
        $text = nl2br($text); 
     
        return $text;             
    } 

    /**    
    * Метод очистки текста от BB-тегов 
    *
    * @param string $text 
    *
    * @return string   
    */             
    public function stripBBtags($text) 
    { 
        $text = str_replace($this->bbOpen, '', $text); 
        $text = str_replace($this->bbClose, '', $text); 
        $text = str_replace($this->bbSingle, '', $text); 
        $text = preg_replace('~\\[(code|url|img|html)[^\s]*?\].*?\[/\\1\]~usi', '', $text);       
        return $text; 
    }      
  
    /**    
    * Аналог wordwrap()  для кодировки UTF-8 
    *
    * @param string $text 
    * @param int $width    
    * @param string $break 
    *
    * @return string   
    */            
    protected function mbWordwrap($text, $width = 74, $break = "\n") 
    { 
       return preg_replace('~([^\s]{'. (int)$width .'})~u', '$1'. $break , $text); 
    } 
    
    /**    
    * Метод добавления ссылок
    *
    * @param string $text 
    *
    * @return string  
    */  
    protected function addLink($text)
    {
        if ($this->links) {                    
            $text = preg_replace_callback('~\[url=http(s*)://([^\] ]+?)\](.+?)\[/url\]~si',  
                                          [$this, 'createLink1'],  
                                          $text 
                                          );
         
            $text = preg_replace_callback('~\[url\]http(s*)://(.+?)\[/url\]~si',  
                                          [$this, 'createLink2'], 
                                          $text 
                                          );                         
        }  
        
        return $text;
    }
    
    /**    
    * Метод добавления картинок
    *
    * @param string $text 
    *
    * @return string  
    */  
    protected function addImg($text)
    {
        if ($this->images) {     
            $text = preg_replace_callback('~\[img=([^\]]+?)\]http://([^\] \?]+?)\[/img\]~si',  
                                          [$this, 'createImg1'],  
                                          $text); 
         
            $text = preg_replace_callback('~\[img\]http://([^\] \?]+?)\[/img\]~si',  
                                          [$this, 'createImg2'],  
                                          $text 
                                          );  
        } 
        
        return $text;
    }
    
    /**    
    * Метод генерации ссылок с текстом
    *
    * @param array $match 
    *
    * @return string  
    */  
    protected function createLink1($match) 
    {   
        $match[2] = str_replace("\n", "", $match[2]); 
        return '<a href="http'. $match[1] .'://'. htmlspecialchars($match[2])  
              . '" target="_blanck" >'. htmlspecialchars($match[3]) .'</a>';  
    } 
  
    /**    
    * Метод генерации ссылок с URL
    *
    * @param array $match 
    *
    * @return string  
    */ 
    protected function createLink2($match) 
    {   
        $match[2] = str_replace("\n", "", $match[2]); 
        return '<a href="http'. $match[1] .'://'. htmlspecialchars($match[2])  
              . '" target="_blanck" >'. htmlspecialchars($match[2]) .'</a>';  
    } 

    /**    
    * Метод генерации картинок с центровкой
    *
    * @param array $match
    *
    * @return string  
    */ 
    protected function createImg1($match) 
    {   
        $match[2] = str_replace(["\n", '?'], "", $match[2]); 
        return '<img src="http://'. htmlspecialchars($match[2]) .'" border="0" ' 
               . 'align="'. htmlspecialchars($match[1]) .'"/>';  
    } 
    
    /**    
    * Метод генерации простых картинок 
    *
    * @param array $match
    *
    * @return string  
    */ 
    protected function createImg2($match) 
    {   
        $match[1] = str_replace(["\n", '?'], "", $match[1]); 
        return '<img src="http://'. htmlspecialchars($match[1]) .'" border="0" />';   
    }  
} 
