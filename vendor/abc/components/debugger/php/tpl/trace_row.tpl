

        <tr class="abc_trace">
            <td width="30px">
                <?=$num; ?>
            </td>
            <td>
                <?=$space; ?>
            </td>
            <td>   
                ..<?=$location; ?>:
                <?=$line; ?>
            </td>
            <td>
                <code>
                <a href="#" onclick="return visibleBlock('n_<?=$num; ?>')"><?=$action; ?>()</a>
                </code>
            </td>
            <td>
                <?=$file; ?>
            </td>
        </tr>
        <tr class="abc_excerpt" style="display:none" id="n_<?=$num; ?>">
            <td colspan="5">
                <?=$php; ?>
            </td>
        </tr>  


