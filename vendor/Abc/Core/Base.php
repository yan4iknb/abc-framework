<?php

namespace ABC\Abc\Core;

use ABC\Abc\Core\BaseTemplate;
use ABC\Abc\Core\Exception\AbcError;

/** 
 * Класс Base
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class Base
{
    
    public $abc;   
    public $TplConfig;  
    public $model;
    
    /**
    * Выбор шаблонизатора и установка шаблона
    *
    * @param string $template
    *
    * @return void
    */     
    public function selectTpl($template)
    {
        $this->TplConfig = $this->abc->getConfig('template');
        $tplType = $this->getTplType();
        $this->tpl = $this->abc->newService($tplType);   
        $this->tpl->selectTpl($template);
    }     
    
    /**
    * Вывод в поток
    *
    * @return void
    */     
    public function render($content = null)
    {
        if (is_object($this->tpl)) {
            $content = $this->tpl->getContent();
        }
       
        $this->abc->getFromStorage('Response')->sendContent($content);
    }    
 
    /**
    * Возвращает объект шаблонизатора
    *
    * @return bool|object
    */        
    protected function getTplType()
    { 
        if (!isset($this->TplConfig['abc_template']) || true === $this->TplConfig['abc_template']) {
            $tplName = 'Template';
        } elseif (false === $this->TplConfig['abc_template']) {
            $tplName = 'TplNative';
        } else {
            AbcError::badFunctionCall(ABC_INVALID_DEBUG_SETTING);
        }
        
        return $tplName;
    }    
    
    /**
    * Возвращает объект модели
    *
    * @return array
    */ 
    protected function model()
    {
        if (is_object($this->model)) {
            return $this->model;       
        } 
     
        AbcError::badMethodCall(ABC_NO_MODEL);
    }
    
    /**
    * Ошибка вызова метода
    *
    * @param string $method
    * @param mix $param
    *
    * @return void
    */     
    public function __call($method, $param)
    {
        $method = explode('::', $method);
        AbcError::badMethodCall(array_pop($method) .'() '. ABC_NO_METHOD);
    } 
    
    /**
    * Генерирует 404 Not Found
    *
    * @param string $search
    *
    * @return void
    */    
    public function action404($search = null)
    {
        if (isset($this->config['error_mod']) && $this->config['error_mod'] === 'debug') {
            throw new \DomainException('404 <b>'. $search .'</b> not found ', E_USER_WARNING); 
        }
        
        header("HTTP/1.1 404 Not Found");
        $page = <<<EOD
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="origin" name="referrer">
    <title>Page not found</title>
   </head>
   <body>
        <div style="text-align:center">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUsAAAB/BAMAAACUBf2iAAAAMFBMVEX/////////gQ7+TDr/xgEsTmX39PO0v8Zbc4SSoq3/5s7/yErU2t7/y6b+hGz/x3uR5maHAAAAAXRSTlMAQObYZgAAAAlwSFlzAAAOwwAADsMBx2+oZAAADF9JREFUeJztnM9rHMkVx/dPSG2gJM0PD5nDHmzWPgxZlkB0EnuwWXwIremBuYwtjUa27DCHkWTQxYQej8AERGhJA7rsWuzBwQGLYQ/xYZnDSDL4opXYw4Jt1gIHAlGwETnEbJz3XlV1V3W3JGP1/Fjww/R0V9f0fLqqvq9e/ZA/YnFYGSyWBx1hv/oohodce3aQy40e/FiK4VnRZmBmHqF9pd/PZHU7eBuBwp/lpB180xPMiTG0h0djgp37IUi5m/NsNHizK5h7hHnxWMxs9j9HUgKnURVdwmwR5uUTMLM7eoa/5Qwbjbl98s0QZoIoxy7pvxSF+al2P50L2MtYKbdWiyHMjMAc0ysuClMrTrPKY6927ljjIczrEvPVSZhnvduhwoy5OOet2yHMdYn5xUmY57xmoQrz4OnTg/0utM4haymE2ZKYXwYxP70v7fkLs9bTuh+6Jjl3WHw2ZLWDmFxSjl0KYvqVnHhNmD/Jy3uGt+SC83cxYo5YpSBmWmHqGgpgsoTeOCWX1xrTsdd6xWZBTFLQm4CGgpjse80lCazf+1iidGPU+mQ+hEkKehDQUAjzOiZcEOc3tbbIt5oLkvsnFpvViiHMQ2yWPKChEGZGw9zV6njesqxlRq3gs/gw3XoQUwIemhoKYaZ9TK5BTVtrj60ldpVaQWyU3LkdxEyL6l43NXQcZlqrc7fAeKMkkkZjw0xZ1SBmUojnuqmhaEwhoTN+nQ9ZUDsL4Ahyv/1TLrda9X5nVh2n1qBwHs+K5JkmuUMR88O/x8I7bq3QZ6rRZkOL4rFtxssGpixG6ti/PQFTJGg1XLGW6Pf4vmXnPreKKvcknvFagU058Ivblk0vNW1ZBThJOXbDLqWcZe5QE3wskiFbnbvk16ft0pRjz+qYhyI4SpghZ7SEPqFTUtBnIw0wy8LjIlu1yOylebx2bMuup+guJDYcPOAdzLKYomsbjnZDfIG+eXekgamUAe5hol33MXlLhpotQ0MhzCuY8IpO94X3wapz7DLVYHn1iT1lOctQVeXyNIq/xMqdVXu6MGMtu2tT1izcqdibcMXKDnDMlt3VYtnJQ3LH3iy7xRKbLnaKHduGh5U7+Zo950ARe5heKWKpjpWOxKTekiQmhL4j2pB0w6m73K7cnaGvc3eRqo43pgpulVu3ClISbpFxaCQp+M5qiRdGxlmniKqGQ6cADahdKTqbFA7X8naVQZPxMZNqGERtdOcozBu+grjf5Qyr1piqctsVemCVQmkE22KqOFIAP2A5SyKSSCEinADeUJENjY/U2XBRBkPYOW6xSl6+tWvdFS7ew/TokoaGApg3NvBajIYSvtAnrbrKkbIL4oQ7VSDFk+ow3E6hfIYRexjCiW04qRRZZYkNL+FhHFXYRkx83mSjLh8Bl6QvD9Or67ShIT2Qe/D0fxR4XBBNQvORNSwJYUOKeAQQO6KQO0BA6R0spQ7qvo6lzaDgJ6sdeB28h1GGwOzI8C2F39nGC4XJPeVwY9gWFRbLoaWG6cjnIp3yyDX4CeFo0PdDGVapVeJVkxzP0HIKoDrtVTjAW7r4CpOE6RbUK689WcV69zA1P9TSNRSB+U9564xym0+2LGvhyRNBKn4I3haqVsbeVG2TVH+36Z3slZJX4LUZPMB3UUGiSJkzrl4Z7E5Jw9S8+p6uoQjMH03M1C3hK62VuVk2N+cW5sDaW/P23Nw8pFX53C3rjki/BdftLauwAjkWONyHNIcOmJyHVNeeTYn8mKFmO1YTTqse5oTfR07oUx9Rlf5fszRnAAd+aqFcBh9ZduvkQHkHPh2L0qatTTivoyOFO9DgpI8td4qQvzZbnsqrZCev8mOGTr5izZbxGQpzz484MrqGIodsP5iVDiIdV02TO0vixEX3YzN0f6h39EJ4sinOhKHCXKhmVBDHMWSKHjQsQ3bmjqcCI0ut70noGorEFFL3MTvyYYyRQyQDd9RpFPgdJhwfecxaEVw6/TKnPHgDfb1blZgj9AoqZGfObfL5PmZCi4aF6EvHYGZfmpiuUjf8kKegdqrRyY+g1FFB5DHdeqcOd9AZ0cuAuFFV0yRszNWhEZoK2bGrgiypZQ8zo48tDrWQU7j3srRrD8TI8oKByTV/VCmoUp3dhr55vi1+jXpBbq01UO7QaTboZWr25raVnxEhOsBxh8rRve3XTMcubfvu3ZDNunYRGlkKTnwJz29CB+Pd76heE6TfrlFXT/XtUsVDwIF9waKr3L5lYZhELwmefZrqnF6L0RdL0AwWNfeuKUgOMS9GY8oR8EMdc0QFHkw4dYl5F4CqTCrCoZZHRQ2Bky1KH/xiYdoi/43RZ8MpqEIkwy/Cc/DhEpNc+iNpLU1DIUwxAsYU2acPNV2r0CRbYbfcRTprQ4jbbLp2s7nM8di0IH0VD+3HTQvzr3BMhRTMSMkuXjXXhpqOeJj8IiQuSczEWMikhsKY3tAyISKk1JxL7hg9Mtv6C6b9A7z1nDRw74apOws8MnkO3Hsog3LvmTCmcqIhTBplnGcqkNsxhC4i+hgH6tIE5vUIzFdHYHJqnFjWMnrXhc78ULkLmOsRmF8ci4llLcZChtBFRB//BLzAbEVgfnkEJvMw7wnHqQtdtNf414cIk0dQyq7zOMybQuoVf7wrnVSXMElBlx755mvouEqXsx5ajy7JY6cUmEnNn5O1PA2FMROehEQNvwwJPc55WB1T7x3J9jwNHeGQCFPIelQTulBQvGsuPuah74CETXgaCmPe9GIPOevq+EI/o01+xY7JNXcuLONpKIxJsYc215X73Bf6bpeETphpX9jSEh54CPOPWT9F9EN+Jd/IxTu9aWImfTcpjXsaCmI+EHHxK3ElSk8trCUCKwYxY677nY4yaq3fMnM64f59OZ8gJ5HU5HtulNbR1bpQNxaBETOkIEl+kR01yJBz79Il5WiV7Zmk7EadI6aoYbMMkqq5RmOqKabpP+dCFn94JDBJL5dMeYrIrnQUpnonN78fpOxCTykwqeQum8kJFctFYqrpGQjh/hDE7M42CsD02qFuLdkxRWFe8ApsqrEYKM6utEzCPAx2lWh7kj0C89yOnjFhcHanyhFTKGgnkD4hW0IY83ygWm9onN3akQKYiVZYQaq7LIUw//1ziOTagaL8V7coT97VVTYtKgt/gDsTerer6/2tfO2bwd8j1337gBmnfcCM0z5gxmkfMOO002Dyt2/fmgnP3zx6FO70yZ4dHJyiLz0NZjIwbE5viPAkKjJOn278fhrMQxMz4YVRO+G89/qGmQ5MQrz2A+cQED/lbMgpMNdNzKQWlX4SzHumb5g8MKVDhfnd0zfEGSTa7xtm0sSkMB/3LdAk00Mzb/q0U2Dvj3loYn6dVXNNf82ae86ZmmHsB2ZizMTc8KqavwjWutw12w/MCRMznfUn7q5kvSk7YWIetC+YLRMzqaElglrf7RsmDpD3NMyv9YreMBsnztvt9gcTEC+ua5iv/dlEWiQ+p+W9CqPjX/cFEwX0SsfM6nPKV0wNgYBe9gfzOhJqmNgcf+PdTRr9Ogroq/5gtuQ2ZIWZNlx6xpD6Ls7T9QUzQ9PLGqYJZpQtpxnkvmCu02ydhmlWM9cxb9IiZj8wuZgQ1TA/zvoz3cwU1D5tje8HZlLMy2uYAW1rvTpGHTv9wTwUk+AaJnp3LYPm3++Jhes+YCbk7PKxmNLZc7kE0wdMhadhfm9i+n3SGYnXB8yWXFEwMfXu8bX+RzC0C6D3mEm1JvcOmImcXF7vPeaeWi5+B8yraudHzzET3jqsiXk+CnNf/T1RzzEnPLiTS5Oijv5gtry195Mxd73VwV5jZvxF7RMxEzlv3brXmOv+avGJfvOqv4jZY0w+ZmzuPaIXeiE6y31/61SPMWXU8U6Y6Zy/J6nHmBB1XFYbVHBkCR+4HyUcIZ0VUYfKi/Me+NkjzLGwoa+PjDdDGyzef5dfTJhJA1NG74OHmTEGGYlBxUwbQzZ5NXiYkeP0PmJqW2ZpGz98/B3TjVkPKahnB74hMn72CFO3wBySP7sV8KJofRmnC1sPzMh5Nzb0eS9hA4L5sSb10PwmGxjMtIaWzAZmi9nAYHJt1WojG15xGRBMCuVI8/SfQASb5sBgJtViZTpiXWhwMMUfF/x8/3nkKtvAYIq/r5d2NpR3YDBp1UpaGGhgMOVfQWQjvBEbJEy5c9/7G2HD+ogZssSbjey577q17/0XYB8w47RfCub/AXyqkAgP/ulfAAAAAElFTkSuQmCC" border="0" />
        </div>
    </body>
</html>
EOD;
        exit($page);
    }
}
