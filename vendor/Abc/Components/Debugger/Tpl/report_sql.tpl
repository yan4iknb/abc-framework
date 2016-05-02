<div class="abc_dedugger">
    <div class="abc_level" >
    {$message}
    </div>
    <div class="abc_mess">
         <strong>
         Query in: </strong>{$file} 
         <strong> on line: </strong>{$line}
         <br />
         <span class="abc_mysql_error">{$error}</span>
    </div>
        <div class="abc_listing">
            <div class="abc_num">
               <code>
               {$num}
               </code>
            </div>
            <div class="abc_code">
                <code>
                    <pre style="color:#990099">
                    
                        {$sql}
                    </pre>
                </code>
            </div>                             
            <div class="clear"></div>
        </div>
        {$explain}
        <div class="abc_mess">
    PHP:
        </div> 
        {$php}
        <div style="border:#8C999D 1px solid;">
        &nbsp;
        </div>   
</div>