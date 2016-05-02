
<div class="abc_dedugger">
    <div class="abc_level" >
        {$level}
    </div>
    <div class="abc_mess">
        {$message}
<?php if($adds){ ?>
        <strong>in:</strong> {$file}
        <strong>on line:</strong> {$line}
<?php } ?>
    </div> 
        {$listing} 
<?php if (empty($stack)) { ?>
    <div class="abc_callstack">
    </div>
    <table class="abc_debug" width="100%" border="0" cellspacing="0" cellpadding="0">       
        <tr>
            <th></th>
        </tr>
    </table>
<?php }else{ ?>
        {$stack} 
<?php } ?>        
</div> 

<script type="text/javascript" language="javascript">
    function ge(id)
    {
        return document.getElementById(id);
    }
    
    function visibleAll(num)
    {
        var i = 1;
        while(i <= num)
        {
            ge('n_' + i).style.display = 'table-row';
            i++   
        }
        ge('visible_steck').style.display = 'none';
        ge('hide_steck').style.display = 'block';
        return false;
    }
    
    function hideAll(num)
    {
        var i = 1;
        while(i <= num)
        {
            ge('n_' + i).style.display = 'none';
            i++   
        }
        ge('visible_steck').style.display = 'block';
        ge('hide_steck').style.display = 'none';
        return false;
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
        ge('abc_debug_lines').innerHTML = lines;
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
        ge('abc_debug_lines').innerHTML = lines;
        var i = 0;
        while(i < cntAnn)
        {
            ge('a_' + i).style.display = 'block';
            i++   
        }
        ge('abc_visible_ann').style.display = 'none';
        ge('abc_hide_ann').style.display = 'block';
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
        ge('abc_debug_lines').innerHTML = lines;
        ge('abc_hide_ann').style.display = 'none';
        ge('abc_visible_ann').style.display = 'block';
        return false;
    } 
</script>
