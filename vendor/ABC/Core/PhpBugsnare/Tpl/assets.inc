<style type="text/css">
.dedugger{margin:20px;font-size:14px;padding:0px;text-align:left;font-weight:normal;}
.dedugger a{color:#0000DD; text-decoration:none;}
.dedugger a:hover{color:#FF7575;}
.dedugger pre{margin:0}
.dedugger .clear{width:100%;clear:both;}
.dedugger .level{color:red;background:#FECBCC;padding:5px}
.dedugger .mess{color:black; background:#F5EDB1;padding:5px;}
.dedugger .args_head{background:#F5EDB1;padding:15px 5px 5px 15px}
.dedugger .args{padding:5px;border:1px solid #78ADFE;background:#E4FFFF}
.dedugger .debug{border-collapse:collapse;}
.dedugger .debug th{border:#8BA0A9 1px solid;background:#D8D8D8}
.dedugger .debug td{border:#8BA0A9 1px solid;}
.dedugger .callstack{background:#E9E9E9;padding:5px;border-top:#8C999D 1px solid;border-left:#8C999D 1px solid;border-right:#8C999D 1px solid}
.dedugger .trace td{background:#F0F0F0;padding-left:5px}
.dedugger .excerpt{background:#E4FFFF}
.dedugger .listing{padding:0;background:#F4FFFF}
.dedugger .num{float:left;width:4%;background:#7273AD;color:#fff;text-align:right;padding-top:0;font-size:15px;}
.dedugger .code{float:left;padding:0px;font-size:15px;overflow-x:auto;width:96%;white-space: nowrap;}
.dedugger .code_value{padding:0;padding-bottom:30px;background:#F4FFFF;color:#373737;overflow-x:auto;width:96%;white-space: nowrap;}
.dedugger .error_line{background:#FF2D2D;color:#FFFF00;width:100%;display:inline-block}
.dedugger .trace_line{background:#FFFF00;color:#FF0000;width:100%;display:inline-block}
.dedugger .tpl{color:#0000A8;}
.dedugger .tpl_error{color:red; font-weight:bold}
.dedugger .explain{border-collapse:collapse;}
.dedugger .explain th{border:#8C999D 1px solid;background:#D8D8D8;text-align:center}
.dedugger .explain td{border:#8C999D 1px solid;background:#F0F0F0;text-align:center}
.dedugger .php_line{background:#FFFFB0;width:100%;display:inline-block}
.dedugger .type{font-weight:bold;font-style:italic;color:#009500}
.dedugger .object{font-weight:bold;font-style:italic;color:#0000FF}
.dedugger .property{font-style:italic;color:#86407E}
.dedugger .property_var{color:#047DCE}
.dedugger .property_value{color:#FF0000}
.dedugger .method{color:#009300}
.dedugger .value{color:#CE001A}
.dedugger .size{font-style:italic;font-size:12px;color:#797979}
.dedugger .annotation{color:#ff8000;}
.dedugger .method_name{color:#0000A0;}
.dedugger .location{color:#D50000}
.dedugger .extends{color:#008000;}
.dedugger .translate{color:#FF0000}
</style>
<script type="text/javascript" language="javascript">
    function ge(id)
    {
        return document.getElementById(id);
    }
    
    function visibleBlock(id)
    {
        var display = ge(id).style.display;
        ge(id).style.display = (display == 'none') ? 'table-row' : 'none';
        return false;
    }
    
    function visibleArg(id)
    {
        var display = ge(id).style.display;
        if (display == 'none') {
            ge(id).style.display = 'block';
        }else{
            ge(id).style.display = 'none';
        }
        return false;
    }
    
    function visibleAnnot(id)
    {
        var display = ge(id).style.display;
        if (display == 'none') {
            ge(id).style.display = 'block';
            lines = addLines(annotations[id +'_']);
        }else{
            ge(id).style.display = 'none';
            lines = deleteLines(annotations[id +'_']);
        }
        ge('debug_lines').innerHTML = lines;
        return false;
    }    
    
    function addLines(cnt)
    {
        var lines = '';
        cntNoAnn = cntNoAnn + cnt;
        for (i = 1; i < cntNoAnn; i++) {
            lines += i + '<br>';
        }
        return lines;
    } 
    
    function deleteLines(cnt)
    {
        var lines = '';
        cntNoAnn = cntNoAnn - cnt;
        for (i = 1; i < cntNoAnn; i++) {
            lines += i + '<br>';
        }
        return lines;
    } 
    
    function abcVisibleAllAnnot(cntAll, cntAnn)
    {
        cntNoAnn = 0;
        lines = addLines(cntAll);
        ge('debug_lines').innerHTML = lines;
        var i = 0;
        while(i < cntAnn)
        {
            ge('a_' + i).style.display = 'block';
            i++   
        }
        ge('visible_ann').style.display = 'none';
        ge('hide_ann').style.display = 'block';
        return false;
    } 
    
    function abcHideAllAnnot(cntAll, cntAnn)
    {
        cntNoAnn = 0;
        lines = addLines(cntAll);
        var i = 0;
        while(i < cntAnn)
        {
            ge('a_' + i).style.display = 'none';
            i++   
        }
        ge('debug_lines').innerHTML = lines;
        ge('hide_ann').style.display = 'none';
        ge('visible_ann').style.display = 'block';
        return false;
    } 
</script>