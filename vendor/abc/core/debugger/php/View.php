<?php

namespace ABC\abc\core\debugger\php;

/** 
 * Класс View
 * Подготавливает HTML для вывода отчета дебаггера
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/   
 */   

class View
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
    * Возвращает HTML блока отчета
    *
    * @return string
    */  
    public function createBlock($data)
    { 
        $data['lines'] = implode('<br>', $data['lines'][0]);
        $tpl = $this->getTpl(__DIR__ .'/tpl/block.tpl');
        return $this->parseTpl($tpl, $data);
    }     
    
    
    /**
    * Возвращает HTML трассировки
    *
    * @return string
    */ 
    public function createStack($data)
    {
        $tpl = $this->getTpl(__DIR__ .'/tpl/stack.tpl');
        return $this->parseTpl($tpl, $data);
    } 
    
    /**
    * Возвращает HTML ряда трассировки
    *
    * @return string
    */     
    public function getStackRow()
    {
        return $this->getTpl(__DIR__ .'/tpl/stack_row.tpl');
    } 
   
    /**
    * Возвращает HTML листинга с содержимым переменной
    *
    * @return string
    */  
    public function createListingVariable($data)
    {
        $data['lines'] = implode('<br>', $data['lines'][0]);
        $tpl = $this->getTpl(__DIR__ .'/tpl/listing.tpl');
        return $this->parseTpl($tpl, $data);
    } 
 
    /**
    * Возвращает HTML листинга с содержимым класса
    *
    * @return string
    */  
    public function createListingClass($data)
    {
        $arrStr = $data['lines'][0];
        $amount = $data['lines'][1];
        
        if (!empty($amount)) {
         
            $cntAnn    = count($amount);
            $cntStrAnn = array_sum($amount) + $cntAnn;
            $arrNoAnn  = array_slice($arrStr, 0, -$cntStrAnn + $cntAnn);
            $cntNoAnn  = count($arrNoAnn);
         
            $i = 0;        
            $arr = $vars = [];
            $scripts = '<script language="javascript">var cntNoAnn='. $cntNoAnn .';</script>';  
            
            foreach ($amount as $cnt) {
                $arr[]  = 'a_'. $i .'_';
                $vars[] = 'annotations[\'a_'. $i++ .'_\']='. ($cnt + 2); 
            } 
            
            $scripts .= '<script language="javascript">var annotations=[\''
                     . implode("','", $arr) .'\'];'
                     . implode(';', $vars) .'</script>'; 
         
            $data['lines'] = $scripts 
                           .'<span id="abc_debug_lines">'
                           . implode('<br>', $arrNoAnn)
                           .'</span>';
            $data['total'] = '<span id="abc_visible_ann"><a href="#" onclick="return abcVisibleAllAnnot('
                           . (count($arrStr) + $cntAnn * 2) .','. $cntAnn .')">'
                           . $this->openAllAnnotationImg() .'</a></span>'
                           . '<span id="abc_hide_ann" style="display:none"><a href="#" onclick="return abcHideAllAnnot('
                           . $cntNoAnn .','. $cntAnn .')">'
                           . $this->closeAllAnnotationImg() .'</a></span>'
                           . '<br>'. $data['total'];
        }
        else {
         
            $data['lines'] = implode('<br>', $arrStr);
            $data['total'] = $data['total'];
        }
        
        $tpl = $this->getTpl(__DIR__ .'/tpl/listing.tpl');
        return $this->parseTpl($tpl, $data);
    }  
    
    /**
    * Возвращает HTML листинга с содержимым контейнера
    *
    * @return string
    */  
    public function createListingContainer($data)
    {
        $data['lines'] = implode('<br>', $data['lines'][0]);
        $tpl = $this->getTpl(__DIR__ .'/tpl/listing.tpl');
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
        $tpl = preg_replace('#\{\$(.+?)\}#i', '<?=$\\1?>', $tpl);
        extract($data);
        ob_start();
        eval('?>'. $tpl);
        return ob_get_clean();
    }
    
    /**
    * Картинка для открытия всех аннотаций
    *
    * @return string
    */     
    public function openAllAnnotationImg()
    {
        return '<img src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAATCAYAAACQjC21AAAACXBIWXMAAA7DAAAOwwHHb6hkAAADcElEQVR4nJVUS4scVRg99ezHVE93KtOPiWaCM8GFMBIRX7gQIQvBVXCRjZuQnS5cBMSNi/wBcaOCoAtBF0YRogu3UVF8ETQMpp1RZpLJpHtqpru6ul63qu69flUzId0zCcQPirp1H+eec77TrfR/fn1Fr9iPqbqNyRIKfK26fK5x4vQX+B+lQ9Use/llGvYmpjVCbFr+7e4lp/vRO/X2C5/BqB46bKhwlMqxG5NzSv+XNzZaT51ZAFYPbFfo6SAZjhH2bkIKeQhQ8hiBP3xt4fl3P7jLkEeQiQcpw3sI+Ad6tYTZxfn9C6aLJwG8qz0+JVmKCDweINrpIgtG9wC9fwmeQsb+087K++SIDb35zOUCULAhydpC49FXoRozE5JxYHz4PfsIzkupno+2f8X436SrCx5bPB5CkvrSXIc2reVipqUJFV5wFGlWoTFHEIZo2QFmZ5L9HTVwtgR3c8vSJY80zlyIjNHCmJxOpsAyrmLgHcdMdRmmYWBz28PQDeDs/o1nH3egFGTpnChR44RFDBl5OCI/YsgsRG7BnWKJhqG/hNn6KYTCQM0EduNNXO5/A6b08OdahBftk1iqdSCEoMYKSxUiI7ohMYwhqGuCxcWThAm2tk/ArD6BAdMxiCT+cCR+FN+hfizEfKeCFdfFh2tXEPs+nc9TwivUlJQAQvCUup34lK2gYBfFZWz2j8CsqxhmEi45UdKBjfEID5Uj3PB+A5MZxkyhy32oWc5Qyz3kpkhZIVnkgMQ0LxMxmsYPWF2JUV9cwHbE0DIFYm8HLgZ7nlDWw5Dh1niHAA2EydwpXVG1CmnPY0/SKeD8bsCPHyXm2Q7e/ukqMU3BaC0WIaxqu1hXKTYbfIRz3a+QEUaDP3xW1yh3eUBVaYANetSU6V+MbWQE0sdz7WU41JBU6NQgl+YYEiIx37Done/huLm1Dl05cvpbd/Xjl9JhhHjwPTEVe7Hdzy6TEh7ZsB6uY5Ds0jcvvGOCF+OYmprsf3thunest3blbOSP6gfyXFQiuHnx2icXr/vX7IwAxmwM82QGURZgKYH+pd+qlWq9/K9jsbx0GOB+JaU0Hdd58s3PL1ya+9SUna/LsvVlSb7y3pm3JvepDwqoKErSbDR/L2sVB+LOLQSQTUPoD85xr1ozHcjrJoLbMTSuoW23rcn1/wAQeOyu3/MtCwAAAABJRU5ErkJggg=="  alt="Visible annotations" border="0"/>';
    } 
    
    /**
    * Картинка для закрытия всех аннотаций
    *
    * @return string
    */     
    public function closeAllAnnotationImg()
    {
        return '<img src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAATCAYAAACQjC21AAAACXBIWXMAAA7DAAAOwwHHb6hkAAADnElEQVR4nI1US2hcZRT+7mPm3nnlTjKTsXlMoohNDCgktCrqQgtWxWIFXQgiblQUii4UrKC4cKcbd120oO4EhW4UAi58LKxRK9ikpurkMUnapjeTO4/7mPv6/+u5SZpME4U5m3v+c///O993zvl/wbhy5krYnBuDkECnCcnCkjLw1PlcafI9QRDa6NIEfea1qDj1KgSZd4YBBphX5+D7wmxu4PgLSvbQbHeAv7wR9R99kdw/9v2KGffBbzCYKxfAPSIZHQQI3Q0kRl850X/7sW/itRwxj6ItRNF+VfG6hURGRd/EQ+Sr/8moufAtmmZtdy1z3gZza/BbqwjbJm6hEW2r3/V3qrEbp69nVMlPP2GvTQNKfp0YuiTHgFmdR2b4BCS10IGCDn//d9vXNECDcCpi1ilz8TxJ5h4YAbLAJ8B7aU+FNgYHpNntFFy/AB5xeH6AWNlwyYYo3qQ7irZeIMnMbTLX0FjgAtwCxFtrySMBDbMHknQEmVQf2l6A5ZqBG3oLGWUehby3Q7ZJSbwdhm4DPCTpob3TjD2wWvM2JNX7EMm9cJgAJSEhRawCl8E2ffSmt/cLokOAbswwBPcdkkyBwETEne0eENiKPoRc8WFYUQbMDLC5vIpiVsLA0BAWqw1qpIXQug4uipAGVSLkWMQwpBpSp0ky9/cAOReg6wI8yUdWU+D8NoN7zCqalb/wz/AkDJ5E8volsI0EojvugrO+hFCyGI0NQxT6W5KZR/PInF3Jdw9ewuyiBXZ5A3eqEpKHBlAqj4D/9BUK3EBvfgiJI4+CqzkIfzZQb/4OWZZVksepWzJCr0FXbg9Q3gKdgz6zAOvHdaTGxmm+UyhoWWTrHqSkCm/+IoJWC5vVCqzDNp2R84Zv39CiUIZzdZG03uza3oDnH5RxrSXA/fk7FLLERhIR33xfX0Xb9VEjZcHjY0iPliFr42+/fO3XDz9y6zac5t9x9Q6M9FaT0saI4Rn9SYPurkS3lWIh5bR8oF6UoQgbSGvPfNZ55n9t/eLMMf3jd77uufxDSpEjMJESKFSd+L2g/BZPwHrg6aWx0+9OSt0Avj4xcs75/ovDqhDSPAJOLoVNpQRVdSGGnJ5Smkt7rdcrjmfFbgBzjz17Mpg6fqGRkWH3pWAcfX5FO332rfrEk5WwJMMtqnCnTk73TD3yQVeSYzOMBW357Lkvg2RmevC5l86Uy+W2ruvZtc8/+dRPqJX733w/ftnZv4zlxvQMou2LAAAAAElFTkSuQmCC"  alt="Close annotations" border="0"/>';
    }       
   
}

