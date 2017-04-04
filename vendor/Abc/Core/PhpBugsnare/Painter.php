<?php

namespace ABC\Abc\Core\PhpBugsnare;

/** 
 * Class Highlight
 * Highlights of listings
 *
 * NOTE: Requires PHP version 5.5 or later   
 * @author Nikolay Twin
 * @copyright © 2015
 * @license http://www.wtfpl.net/   
 */   

class Painter
{  
    /**
    * Highlighting the line
    *
    * @param string $line
    * @param string $type
    *
    * @return string
    */  
    public function wrapLine($line, $type)
    {
        return '<span class="'. $type .'_line">'. $line .'</span>';
    }
    
    /**
    * Highlighting php code
    *
    * @param string $blockCont
    * @param int $position
    * @param int $size
    *
    * @return string
    */    
    public function highlightString($blockCont, $position, $size)
    {
        $descr = preg_match('~^[\r\n\s\t]*?<\?php~uis', $blockCont) ? null : '<?php ';
        $blockCont = highlight_string($descr . $blockCont, true);
        $lines = preg_split('~<br[\s/]*?>~ui', $blockCont);       
        $lines = array_slice($lines, $position, $size);
        return implode('<br />', $lines);
    }
    
    /**
    * Подсветка шаблонов
    *
    * @param string $blockCont
    * @param int $position
    *
    * @return string
    */    
    public function highlightStringTpl($blockCont, $position, $size)
    { 
        $blockCont = highlight_string($blockCont, true);
        $lines = preg_split('~<br[\s/]*?>~ui', $blockCont);       
        $lines = array_slice($lines, $position, $size);
        
        $tplLines = [];
        
        foreach ($lines as $line) {
            $tplLines[] = preg_replace('~(&lt;!--//(.+?)--&gt;)~is', '<span style="color:#35CABB">\\1</span>', $line);
        }
        
        return implode('<br />', $tplLines);
    } 
    
    /**
    * Highlighting class content
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
                                        
        $blockCont = preg_replace("#[^\$](class|extends|implements|static)#", ' <span class="extends">\\1</span> ', $blockCont);
        $blockCont = preg_replace('#[^\$](public|protected|private)#i', ' <span class="property">\\1</span> ', $blockCont);
        $blockCont = preg_replace('#\$([a-z0-9_]+?)\s#i', ' <span class="property_var">$\\1</span> ', $blockCont); 
        $blockCont = preg_replace('#\((size.+?)\)#i', ' <span class="size">(\\1)</span>', $blockCont);
        $blockCont = preg_replace("#\[(.+?)\]#i", ' <span class="method_name">\\1</span>', $blockCont);   
        $blockCont = str_replace(' method ', ' <span class="method"> method </span>', $blockCont);         
        $blockCont = preg_replace("#@@(.+?)\n#i", ' <span class="location">\\1<br></span>', $blockCont);
        
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
    * Highlighting object content
    *
    * @param string $blockCont
    *
    * @return string
    */    
    public function highlightObject($blockCont)
    {
        $blockCont = strip_tags($blockCont);
        $strings = ['#(?<!\'|")object(\s*)#i' => '<span class="type">object</span>$1',
                    '#(?<!\'|")array(\s*)#i'  => '<span class="type">array</span>$1',
                    '#(?<!\'|")string(\s*)#i' => '<span class="type">string</span>$1',
                    '#(?<!\'|")int(\s*)#i'    => '<span class="type">int</span>$1',
                    '#(?<!\'|")null(\s*)#i'   => '<span class="type">null</span>$1',
        ];
        
        if (extension_loaded('xdebug')) {
            $blockCont = preg_replace("#string\s*?'(.*?)'#i", '<span class="property_value">"\\1"</span>', $blockCont);        
            $blockCont = preg_replace('#\((size.+?)\)#i', '<span class="size">(\\1)</span>', $blockCont);
            $blockCont = preg_replace("#'(.+?)'#i", '<span class="property_var">$\\1</span>', $blockCont);
            $blockCont = preg_replace('#(?<!\$)(public|protected|private)#i', '<span class="property">\\1</span>', $blockCont);
        } else {
            $blockCont = preg_replace('#(string[\s|\(].*?[\)\s])[\'|"](.+?)[\'|"]#i', '$1 <span class=ᐃ$2\'</span>', $blockCont);
            $blockCont = preg_replace('#\((size.+?)\)#i', '<span class=ᐅ(\\1)\'</span>', $blockCont);
            $blockCont = preg_replace('#[\'|"](.+?)[\'|"]#i', '<span class="property_var">$\\1</span>', $blockCont);        
            $blockCont = str_replace(['ᐃ', 'ᐅ'], ['"property_value">\'', '"size">\''], $blockCont);
            $blockCont = preg_replace('#(?<!\$)(public|protected|private)#i', '<span class="property">\\1</span>', $blockCont);
            
        }
        
        $blockCont = preg_replace(array_keys($strings), array_values($strings), $blockCont);
        return $blockCont;
    }  
    
    /**
    * Highlighting the value of the variable
    *
    * @param string $blockCont
    *
    * @return string
    */    
    public function highlightVar($blockCont, $obj = true)
    {      
        $strings = ['#(?<!\')object\s+#i' => '<span class="type">object</span> ',
                    '#(?<!\')array\s+#i'  => '<span class="type">array</span> ',
                    '#(?<!\')string\s+#i' => '<span class="type">string</span> ',
                    '#(?<!\')int\s+#i'    => '<span class="type">int</span> ',
        ];
        
        if (extension_loaded('xdebug')) {
            $blockCont = strip_tags($blockCont);
            $blockCont = preg_replace("#'(.+?)'#i", '<span class="value">\'$1\'</span>', $blockCont);
        } else {
            $blockCont = preg_replace('#[\'|"](.+?)[\'|"]#i', '<span class="value">\'$1\'</span>', $blockCont);
        }
        
        if ($obj) {
            $blockCont = preg_replace("#\((.+?)\)#i", '<span class="property">($1)</span>', $blockCont);        
        }
     
        $blockCont = preg_replace(array_keys($strings), array_values($strings), $blockCont);
        
        
        return $blockCont;
    } 
    
    /**
    * Icon to open one summary
    *
    * @return string
    */     
    public function openAnnotationImg()
    {
        return '<img src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAALCAYAAACksgdhAAAACXBIWXMAAA7DAAAOwwHHb6hkAAABu0lEQVR4nHVSTWtTQRQ9M2/y0pcmRFP7idBWQcEPFERw585tV9Jtt10WuuwfaMWFm0L/h6CrguhKRBA/ulBaTXnRvLRm+tLk5c2bO8+bmkVp6cAMc+89555zhxHN9yuaujtVCPCSQHCDKjdXn1QuXd3GBUs03izqqUdLVeEbDgvI4kPo8KgVjM6tSe8UkjLIicevg6BWV46sgXDIbXhSU6UiqlMYTw5ebdEQn+cW3fArPDP2lMO6yl2W2Fij/f0lXJYOYfKcJZcmsLvPN1qfXywxyXIi5m7jGLu/AFX0B65BTsDSf3+ezKH46iiY33/7zFPkMib9gXPHEDKE8EdAJFGPathrzHDeolZp4sHtQ3j5LMj2wEoGZFq8O7D9DosQgy+j05/G3euT3MDiy7efoGTQNGZSmvBDEJzpwRpOJPpk6N29SczMlrFzJOHrvyi6NtdiCE+zxdSoPGeSTZk0UIqh4DBX+4Ff9TLS4gRU+yMe3tqHTSykKjHJQBXK80zo8Vx9pDoCjXRxpRAhziI0wjLu3fkNr0/gMpNYwMljoZuflqMP65tpmwf1S+xbQIgzP2B4yEIF2ei1d/8AyBrngSsY6DUAAAAASUVORK5CYII="  alt="Visible annotation" border="0"/>';
    } 
}

