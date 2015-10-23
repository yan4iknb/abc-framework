<?php

namespace ABC\Abc\Core\Debugger\Php;

/** 
 * Класс Highlight
 * Подсвечивает результаты листингов
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/   
 */   

class Painter
{  

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
    public function highlightString($blockCont, $position, $size)
    {
        $descr = preg_match('~^[\r\n\s\t]*?<\?php~uis', $blockCont) ? '' : '<?php ';
        $blockCont = highlight_string($descr . $blockCont, true);
        $lines = preg_split('~<br[\s/]*?>~ui', $blockCont);       
        $lines = array_slice($lines, $position, $size);
        return implode('<br />', $lines);
    } 
    
    /**
    * Подсветка содержимого класса
    *
    * @param string $blockCont
    *
    * @return string
    */    
    public function highlightClass($blockCont)
    {
        $blockCont = strip_tags($blockCont);
        preg_match_all('#(/\*\*.+?\*/)#is', $blockCont, $annotations);
        
        $i = 0;
        $blockCont = preg_replace_callback('#(/\*\*.+?\*/)#is', 
                                        function ($m) use (&$i) {
                                            return '_'. ($i++) .'ᐁ';
                                        },
                                        $blockCont);
                                        
        $blockCont = preg_replace("#[^\$](class|extends|implements|static)#", '<span class="extends">\\1</span> ', $blockCont);
        $blockCont = preg_replace('#[^\$](public|protected|private)#i', '<span class="property">\\1</span> ', $blockCont);
        $blockCont = preg_replace('#\$([a-z0-9_]+?)\s#i', '<span class="property_var">$\\1</span> ', $blockCont); 
        $blockCont = preg_replace('#\((size.+?)\)#i', '<span class="size">(\\1)</span>', $blockCont);
        $blockCont = preg_replace("#\[(.+?)\]#i", '<span class="method_name">\\1</span>', $blockCont);   
        $blockCont = str_replace(' method ', '<span class="method"> method </span>', $blockCont);         
        $blockCont = preg_replace("#@@(.+?)\n#i", '<span class="location">\\1<br></span>', $blockCont);
        
        $strings = ['object'   => '<span class="object">object</span>',
                    'array'    => '<span class="type">array</span>',
        ];        
        $blockCont = str_replace(array_keys($strings), array_values($strings), $blockCont);        
     
        
        $i = 0;
        foreach ($annotations[0] as $a) {
            $blockCont = str_replace('_'. ($i) .'ᐁ', 
                                 '<a href="#" onclick="return visibleAnnot(\'a_'. ($i) .'\')">'
                                . $this->openAnnotationImg() .'</a>'
                                .'<span id="a_'. ($i++) .'" class="annotation" style="display:none">'. $a .'</span>',
                                 $blockCont);
        }
        
        return $blockCont;
    } 
    
    /**
    * Подсветка содержимого контейнера
    *
    * @param string $blockCont
    *
    * @return string
    */    
    public function highlightContainer($blockCont)
    {
        return $blockCont;
    } 
    
    /**
    * Подсветка содержимого объекта
    *
    * @param string $blockCont
    *
    * @return string
    */    
    public function highlightObject($blockCont)
    {
        $blockCont = strip_tags($blockCont);
        $strings = ['object'   => '<span class="object">object</span>',
                    'array'    => '<span class="type">array</span>',
                    'string' => '<span class="type">string</span>',
                    'int'    => '<span class="type">int</span>',
                    'null'    => '<span class="type">null</span>'
        ];
        $blockCont = preg_replace("#string\s*?'(.*?)'#i", '<span class="property_value">"\\1"</span>', $blockCont);        
        $blockCont = preg_replace('#\((size.+?)\)#i', '<span class="size">(\\1)</span>', $blockCont);
        $blockCont = preg_replace("#'(.+?)'#i", '<span class="property_var">$\\1</span>', $blockCont);
        $blockCont = preg_replace('#(?<!\$)(public|protected|private)#i', '<span class="property">\\1</span>', $blockCont);

        $blockCont = str_replace(array_keys($strings), array_values($strings), $blockCont);
        return $blockCont;
    }  
    
    /**
    * Подсветка содержимого переменной
    *
    * @param string $blockCont
    *
    * @return string
    */    
    public function highlightVar($blockCont)
    {
        $blockCont = strip_tags($blockCont);   
        $strings = ['object' => '<span class="object">object</span>',
                    'array'  => '<span class="type">array</span>',
                    'string' => '<span class="type">string</span>',
                    'int'    => '<span class="type">int</span>',
                    'null'    => '<span class="type">null</span>',
        ];
        $blockCont = preg_replace("#'(.+?)'#i", '<span class="value">\'$1\'</span>', $blockCont);
        $blockCont = str_replace(array_keys($strings), array_values($strings), $blockCont);
        return $blockCont;
    } 
    
    /**
    * Картинка для открытия одной аннотации
    *
    * @return string
    */     
    public function openAnnotationImg()
    {
        return '<img src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAALCAYAAACksgdhAAAACXBIWXMAAA7DAAAOwwHHb6hkAAABu0lEQVR4nHVSTWtTQRQ9M2/y0pcmRFP7idBWQcEPFERw585tV9Jtt10WuuwfaMWFm0L/h6CrguhKRBA/ulBaTXnRvLRm+tLk5c2bO8+bmkVp6cAMc+89555zhxHN9yuaujtVCPCSQHCDKjdXn1QuXd3GBUs03izqqUdLVeEbDgvI4kPo8KgVjM6tSe8UkjLIicevg6BWV46sgXDIbXhSU6UiqlMYTw5ebdEQn+cW3fArPDP2lMO6yl2W2Fij/f0lXJYOYfKcJZcmsLvPN1qfXywxyXIi5m7jGLu/AFX0B65BTsDSf3+ezKH46iiY33/7zFPkMib9gXPHEDKE8EdAJFGPathrzHDeolZp4sHtQ3j5LMj2wEoGZFq8O7D9DosQgy+j05/G3euT3MDiy7efoGTQNGZSmvBDEJzpwRpOJPpk6N29SczMlrFzJOHrvyi6NtdiCE+zxdSoPGeSTZk0UIqh4DBX+4Ff9TLS4gRU+yMe3tqHTSykKjHJQBXK80zo8Vx9pDoCjXRxpRAhziI0wjLu3fkNr0/gMpNYwMljoZuflqMP65tpmwf1S+xbQIgzP2B4yEIF2ei1d/8AyBrngSsY6DUAAAAASUVORK5CYII="  alt="Visible annotation" border="0"/>';
    } 
}

