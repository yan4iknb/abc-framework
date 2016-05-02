<div class="abc_dedugger">
    <div class="abc_level" >
        {$level}
    </div>
    <div class="abc_mess">
        {$message}
        <strong>in:</strong> {$file}
        <strong>on line:</strong> {$line}
    </div> 
        {$listing}
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
    

</script>
