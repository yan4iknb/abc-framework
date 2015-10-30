

        <tr class="abc_trace">
            <td width="30px">
                {$num}
            </td>
            <td>
                {$space}
            </td>
            <td>  
                <code>
                <a href="#" onclick="return visibleBlock('n_{$num}')">{$action}()</a>
                </code>   
            </td>
            <td>
                {$line} 
            </td>
            <td>
                {$file}
            </td>
        </tr>
        <tr class="abc_excerpt" style="display:none" id="n_{$num}">
            <td colspan="5">
                {$total}
            </td>
        </tr>  


