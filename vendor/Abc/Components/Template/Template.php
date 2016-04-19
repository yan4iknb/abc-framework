<?php 

namespace ABC\Abc\Components\Template;

use ABC\Abc\Core\Response;

/** 
 * Класс Template 
 * Шаблонизатор
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */ 
   
class Template  
{  
    /**      
    * @var bool     
    */       
    public  $tplPhp    = true; 
 
    /**        
    * @var string     
    */       
    public  $tplExt     = 'tpl';  
    /**        
    * @var bool    
    */       
    public  $inialize    = true; 
    
    protected $tplDir;  
    protected $leftDelim   = '{';
    protected $rightDelim  = '}'; 
    protected $tpl         = '';
    protected $startDelim  = '';
    protected $endDelim    = ''; 
    protected $data        = [];
    protected $blocks      = [];
    protected $parsed      = [];    
    protected $stack       = [];    
    protected $errors      = [];     
    protected $errhead     = true;   
    protected $total       = null;   
    protected $class       = __CLASS__;  

    /**
    * Constructor.
    *
    * @param string $tplDir      Path to templates directory
    */
    public function __construct($tplDir)
    {
        $this->tplDir = str_replace('\\', ABC_DS, $tplDir); 
    }
    
    /**
    * 
    *
    * @param string $tplName      The name of the template
    * @param string $blockParent  The name of the parent block
    */
    public function setTpl($tplName, $blockParent = '')
    {
        $path = $this->tplDir . $tplName .'.'. $this->tplExt;
        $this->startDelim = $this->leftDelim . $this->leftDelim;
        $this->endDelim   = $this->rightDelim . $this->rightDelim;
     
        if (false === ($this->tpl = @file_get_contents($path))) {
            Response::domainError($path . ABC_NO_TEMPLATE);
        }
        
        if (!empty($blockParent)) {
            $parentOut = $this-> tplPhp ? '<?=$'. $blockParent .'; ?>'
                                          : $this->leftDelim . $blockParent . $this->rightDelim;
         
            $this->tpl = preg_replace('~<!--//\s+('
                                      . preg_quote($blockParent, '~')
                                      .')\s*#*.*?\s+\-\->.*?\\1\s+?end\s*#*.*?\s+?\-\->~uis',
                                       $parentOut,
                                       $this->tpl
                                       );
          
            if (false === strpos($this->tpl, $parentOut)) {
                Response::domainError($blockParent . ABC_INVALID_BLOCK);
            }
        }
     
        $this->initiate();
    }
    
    /**
    * Assign a variable.
    *
    * @example Simplest case:
    * @example $tpl->assign('name', 'value');
    * @example <?=$name ?> in template
    *
    * @example Array assign:
    * @example $tpl->assign(array('name' => 'value', 'name2' => 'value2'));
    * @example <?=$name ?>  <?=$name2 ?>  in template
    *
    * @param string/array $data
    * @param string/array $value
    * 
    * @return object
    */
    public function assign($data, $value = null)
    {
        if (is_array($data)) {
            $this->data = array_merge($this->data, $data);
            $this->stack[]['assign'] = $data;
        } else {
            $this->data[$data] = $value;
            $this->stack[]['assign'] = [$data => $value];
        }
     
        if (!$this->tplPhp) {
            $this->normalise($this->data);
        }
        return $this;
    }

    /**
    * Assign a variable to the processing of html.
    * 
    * @param string/array $data
    * @param string/array $value
    *
    * @return object
    */
    public function assignHtml($data, $value = '')
    {
        if (is_array($data)) {
            $this->data = array_merge($this->data, $this->htmlChars($data));
            $this->stack[]['assign'] = $this->htmlChars($data);
        } else {
            $this->data[$data] = $this->htmlChars($value);
            $this->stack[]['assign'] = array($data => $this->htmlChars($value));
        }
     
        if (!$this->tplPhp) {
            $this->normalise($this->data);
        }
        
        return $this;
    }

    /**
    * Sets the block in the template.
    *
    * @example $tpl->setBlock('content');
    * @example render <!--// content -->...<!--// content end -->
    *
    * @param string $blockName
    *
    * @return object
    */
    public function setBlock($blockName)
    {
        $blockTag  = $this->startDelim . $blockName . $this->endDelim;
        $this->stack[]['setBlock'] = $blockName;
        $block = null;
     
        if (isset($this->blocks[$blockTag]))  {
            $block = $this->execute($this->blocks[$blockTag]);
            $block = $this->parse($block);
        } else {
            $this->errors[$blockName] = true;
        }
        
        @$this->parsed[$blockTag] .= $block;
        return $this;
    }

    /**
    * Clears the contents of the block
    *
    * @example $tpl->clearBlock('row');
    * @example clears the contents of the block "row"
    *
    * @param string $blockName
    * @return object
    */
    public function clearBlock($blockName)
    {
        $blockTag  = $this->startDelim . $blockName . $this->endDelim;
        $this->stack[]['clearBlock'] = $blockName;
     
        if (isset($this->blocks[$blockTag])) {
            $this->parsed[$blockTag] = null;
        } else {
            $this->errors[$blockName] = true;
        }
        return $this;
    }

    /**
    * Parse the template
    * 
    * @return string
    */
    public function parseTpl()
    {
        return $this->prepareTpl();
    }

    /**
    * Rendering the template
    * 
    * @return void
    */
    public function display()
    {       
        if (empty($this->total)) {
            $this->total = $this->parseTpl();
        }
        
        echo $this->total;
    }

    /**
    * Extends the template
    *
    * @param string $tpl
    * @param string $block
    *
    * @return object
    */
    public function extendsTpl($block, $tpl)
    { 
        $child = $this->parseChild();
        $parentTpl = new $this->class($this->tplDir);
        $parentTpl->setTpl($tpl, $block);
        $parentTpl->assign($block, $child);
      
        foreach ($this->stack as $stack) {
            $method = key($stack);
            $parentTpl->$method($stack[$method]);
        }
     
        $this->total  = $parentTpl->parseChild();
        $this->checkBlock($this->errors, $parentTpl->errors);
        return $this;
    }
    
    /**
    * Parses the child template
    * 
    * @return string
    */
    protected function parseChild()
    {
        return $this->prepareTpl(false);
    }

    /**
    * Parses the template
    * 
    * $param bolean $check
    *
    * @return string
    */
    protected function prepareTpl($check = true)
    { 
        $this->tpl   = $this->parse($this->tpl);
        $this->tpl   = $this->clear($this->tpl);
        $this->total = $this->execute($this->tpl);
     
        if ($check) {
            $this->checkBlock($this->errors);
        }
        
        return $this->total;
    }

    /**
    * Collects in the array contents of all nested blocks
    * 
    * $param string $block
    *
    * @return string
    */
    protected function parse($block)
    {
        $block = $this->replace($block);
     
        if (!empty($this->parsed)) {
            $tags = array_keys($this->parsed);
         
            foreach ($this->parsed as $name => $cont) {
                foreach ($tags as $tag) {
                    $this->parsed[$name] = str_replace($tag,
                                                        $this->parsed[$tag],
                                                        $this->parsed[$name]
                                                        );
                }
            }
         
            $block = str_replace($tags, $this->parsed, $block);
        }
     
        return $block;
    }

    /**
    * Executes php code in the template with the given parameters
    * 
    * $param string $block
    *
    * @return string
    */
    protected function execute($block = '')
    {
        if (!$this->tplPhp) {
            return $this->parsing($block);
        }
        
        $block = $this->includesPhp($block);
        $block = str_ireplace('<?xml', '<xml', $block); 
        extract($this->data);
        ob_start();
            eval('?>'. $block);
        $block = ob_get_clean();
     
        $block = stripslashes($block);
        $block = str_ireplace('<xml', '<?xml', $block);
     
        return $block;
    }

    /**
    * Replacing instruction "include" to contents of the include file
    *
    * @param string $block
    * 
    * @return string
    */
    protected function includesPhp($block)
    {
        $pattern = '~(<?[ph=][^\?>]*?)include[\s\'"]+(.*?)\..+?[\'"]+(;*)~uis';
        preg_match_all($pattern, $block, $include);
      
        if (!empty($include[2])) {
            foreach ($include[2] as $file) {
                $md5   = md5($file);
                $cont  = $this->includes($file, $md5);
                $block = preg_replace($pattern, '$1 echo "'. addslashes($cont) .'"$3', $block);
            }
        }
      
        return $block;
    }

    /**
    * Connection files in the template
    *
    * @param array $matсh
    * 
    * @return string
    */
    protected function includesPsd($match)
    {
        $md5  = md5($match[1]);
        $this->data[$md5] = $this->includes($match[1], $md5);
     
        return $this->leftDelim . $md5 . $this->rightDelim;
    }

    /**
    * Parse of external template
    *
    * @param string $file
    * @param string $md5
    * 
    * @return string
    */
    protected function includes($file, $md5)
    {
        $inc = new $this->class($file);
        
        $search = [$this->startDelim, $this->endDelim];
        $replace = [null];
      
        foreach ($inc->blocks as $tag => $val) {
            $bname = str_replace($search, $replace, $tag);
            unset($this->errors[$bname]);
        }     
     
        foreach($this->stack as $stack)
        {
            $method = key($stack);
            $inc->$method($stack[$method]);
        }
     
        return $inc->parseChild();
    }

    /**
    * Replaces the pseudo variables to values
    * 
    * $param string $block
    *
    * @return string
    */
    protected function parsing($block = '')
    {
        $names  = array_keys($this->data);
        $valyes = array_values($this->data);
     
        $block  = preg_replace_callback('~'. preg_quote($this->leftDelim, '~')
                                           .'FILE ([a-z0-9\._]+?)'
                                           . preg_quote($this->rightDelim, '~')
                                           .'~uis',
                                           [$this, 'includesPsd'],
                                           $block);
     
        $tags   = preg_replace('~([a-z0-9\._]+)~uis',
                              $this->leftDelim .'$1'. $this->rightDelim,
                              $names
                              );
     
        $block = str_replace($tags, $valyes, $block);
        return  preg_replace('~<\?[^x].*?\?>~uis', '', $block);
    }

    /**
    * Processing variables for output stream
    *
    * @param array $data
    * 
    * @return mix
    */
    protected function htmlChars($data)
    {
        if (is_array($data)) {
            $data = array_map([$this, 'htmlChars'], $data);
        } else {
            $data = htmlspecialchars($data);
        }
        
        return $data;
    }

    /**
    * Normalization of names array
    *
    * @param array $data
    * 
    * @return void
    */
    protected function normalise($data, $html = false)
    {
        foreach ($data as $name => $value)  {
            if (is_array($value)) {
                foreach ($value as $key => $val) {
                    if ($html) {
                        $val = htmlspecialchars($val);
                    }
                    $names[$name .'.'. $key]  = $val;
                }
            } else {
                $names[$name] = htmlspecialchars($value);
            }
        }
     
        $this->data = $names;
    }

    /**
    * Initialization of variables and sampling blocks
    * 
    * @return void
    */
    protected function initiate()
    {
        if ($this->inialize && $this->tplPhp) {
            preg_match_all('~\$([a-z0-9_]+)~ui', $this->tpl, $vars);
         
            if (!empty($vars[1])) {
                foreach ($vars[1] as $var) {
                    $this->data[$var] = null;
                }
            }
        }
     
        $this->tpl = preg_replace('~(<!--//\s+[^#]+)#*.*?(\s+-->)~ui',
                                   '$1$2$3',
                                   $this->tpl
                                   );
     
        preg_match_all('~<!--//\s+([^\s]+?)\s+-->~uis', $this->tpl, $blocks);
        $this->prepare($blocks[1]);
    }

    /**
    * Recursive extract the contents of nested blocks
    * 
    * $param array $blocks
    *
    * @return void
    */
    protected function prepare($blocks)
    {
        if (is_array($blocks)) {
            foreach ($blocks as $blockName) {
                preg_match('~<!--//\s*'. preg_quote($blockName, '~')
                          .'\s+-->+[\r\n]*(.*?)[\r\n]*<!--//\s*?'. preg_quote($blockName, '~')
                          .'\s+end\s+-->~uis',
                           $this->tpl,
                           $blocksArray
                         );
             
                if (!empty($blocksArray[1])) {
                    preg_match_all('~<!--//\s+([^\s]+?)\s+-->~uis',
                                   $blocksArray[1],
                                   $blocksRecursion
                                   );
                 
                    if (!empty($blocksRecursion[1])) { 
                        foreach ($blocksRecursion[1] as $blocks) {
                            $this->prepare($blocks);
                        }
                    }
                 
                    $tag = $this->startDelim . $blockName . $this->endDelim;
                    $this->blocks[$tag] = $this->replace($blocksArray[1]);
                }
            }
        }
    }

    /**
    * Replaces the block on the token
    * 
    * $param string $tpl
    *
    * @return void
    */
    protected function replace($tpl)
    {
        return preg_replace('~<!--//\s+([^\s]+?)\s+-->\n*.*?\\1\s+end\s*-->~uis',
                            $this->startDelim .'$1'. $this->endDelim,
                            $tpl
                            );
    }

    /**
    * Replaces the block on the token
    * 
    * $param string $tpl
    *
    * @return void
    */
    protected function clear($tpl)
    {
        return preg_replace('~'. preg_quote($this->startDelim, '~')
                               .'.*?'
                               . preg_quote($this->endDelim, '~')
                               .'~uis',
                            '',
                            $tpl
                            );
    }

    /**
    * Checks for the presence of blocks in the template
    * 
    * $param array $errorBlocks
    * $param array/bollean $parentBlocks
    *
    * @return bolean/void
    */
    protected function checkblock($errorBlocks, $parentBlocks = false)
    {
        if (empty($errorBlocks)) {
            return false;
        }
        
        if (is_array($parentBlocks)) {
            $error = array_intersect_key($errorBlocks, $parentBlocks);
        } else {
            $error = $errorBlocks;
        }
        
        if (!empty($error)) {
            foreach ($error as $bname => $v) {
                Response::domainError($bname . ABC_INVALID_BLOCK);
            }
        }
    }

} 