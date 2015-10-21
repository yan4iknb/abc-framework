<style type="text/css">
.abc_dedugger{margin:20px;height:1000px;font-size:14px;padding:0px;}
.abc_dedugger a{color:#0000DD; text-decoration:none;}
.abc_dedugger a:hover{color:#FF7575;}
.abc_dedugger pre{margin:0}
.abc_dedugger .clear{width:100%;clear:both;}
.abc_dedugger .abc_level{color:red;background:#FECBCC;padding:5px}
.abc_dedugger .abc_mess{color:black; background:#F5EDB1;padding:5px;}
.abc_dedugger .abc_args_head{background:#F5EDB1;padding:15px 5px 5px 15px}
.abc_dedugger .abc_args{padding:5px;border:1px solid #78ADFE;background:#E4FFFF}
.abc_dedugger .abc_debug{border-collapse:collapse;}
.abc_dedugger .abc_debug th{border:#8BA0A9 1px solid;background:#D8D8D8}
.abc_dedugger .abc_debug td{border:#8BA0A9 1px solid;}
.abc_dedugger .abc_callstack{background:#E9E9E9;padding:5px;border-top:#8C999D 1px solid;border-left:#8C999D 1px solid;border-right:#8C999D 1px solid}
.abc_dedugger .abc_trace td{background:#F0F0F0;padding-left:5px}
.abc_dedugger .abc_excerpt{background:#E4FFFF}
.abc_dedugger .abc_listing{padding:0;background:#F4FFFF;}
.abc_dedugger .abc_num{float:left;width:4%;background:#7273AD;color:#fff;text-align:right;padding-top:0;font-size:15px;}
.abc_dedugger .abc_code{float:left;padding:0px;font-size:15px;overflow-x:auto;width:96%;white-space: nowrap;}
.abc_dedugger .abc_code_value{padding:0;padding-bottom:30px;background:#F4FFFF;color:#373737;overflow-x:auto;width:96%;white-space: nowrap;}
.abc_dedugger .abc_error_line{background:#FF2D2D;color:#FFFF00;width:100%;display:inline-block}
.abc_dedugger .abc_trace_line{background:#FFFF00;color:#FF0000;width:100%;display:inline-block}
.abc_dedugger .abc_tpl{color:#0000A8;}
.abc_dedugger .abc_tpl_error{color:red; font-weight:bold}
.abc_dedugger .abc_explain{border-collapse:collapse;}
.abc_dedugger .abc_explain th{border:#8C999D 1px solid;background:#D8D8D8;text-align:center}
.abc_dedugger .abc_explain td{border:#8C999D 1px solid;background:#F0F0F0;text-align:center}
.abc_dedugger .abc_php_line{background:#FFFFB0;width:100%;display:inline-block}
.abc_dedugger .abc_mysql_error{color:#FF0000;}

</style>
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