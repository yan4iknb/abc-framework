
<?php if (!$hide){ ?>
        <div class="abc_args_head">
            <a href="#" onclick=" return visibleArg('arg_<?=$num; ?>')">Arguments</a>
        </div>
        <div class="abc_args"  style="display:none" id="arg_<?=$num; ?>">
            <code><pre><?=$arguments; ?></pre></code>
        </div>
<?php } ?>    
        <div class="abc_listing">
            <div class="abc_num">
                <code><?=$lines; ?></code>
            </div>
            <div class="abc_code">
                <code><?=$code; ?></code> 
            </div>
            <div class="clear"></div>
        </div>






