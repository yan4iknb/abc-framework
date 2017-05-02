

        <tr class="trace">
            <td width="30px">
                {$num}
            </td>
            <td>
                {$space}
            </td>
            <td>  
                <code>
                <a href="#" onclick="return visibleBlock('n_{$uniq}')">{$action}()</a>
                </code>   
            </td>
            <td>
                {$line} 
            </td>
            <td>
                {$file}
            </td>
        </tr>
        <tr class="excerpt" style="display:none" id="n_{$uniq}">
            <td colspan="5">
                {$total}
            </td>
        </tr>  
