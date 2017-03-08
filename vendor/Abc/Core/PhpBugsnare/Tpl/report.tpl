
<div class="dedugger">
    <div class="level" >
        {$level}
    </div>
    <div class="mess">
        {$message}
<?php if ($adds) { ?>
        <strong>in:</strong> {$file}
        <strong>on line:</strong> {$line}
<?php } ?>
    </div> 
        {$listing} 
<?php if (empty($stack)) { ?>
    <div class="callstack">
    </div>
<?php } else { ?>
        {$stack} 
<?php } ?>        
</div> 
