
     <div style="color:black; background:#F5EDB1;padding:5px">
        <strong>Query time: </strong>{$queryTime} s
        <br>
        <strong>Explain:</strong>
    </div>
    <table class="abc_explain" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr style="color:##B2B2B2">
            <th>id</th>
            <th>select_type</th>
            <th>table</th>        
            <th>type</th>        
            <th>possible_keys</th>        
            <th>key</th>
            <th>key_len</th>
            <th>ref</th>        
            <th>rows</th>        
            <th>Extra</th>
        </tr>    
        <tr>
            <td>{$id}</td>
            <td>{$select_type}</td>        
            <td>{$table}</td>
            <td>{$type}</td>        
            <td>{$possible_keys}</td>        
            <td>{$key}</td>        
            <td>{$key_len}</td>        
            <td>{$ref}</td>        
            <td>{$rows}</td>
            <td>{$extra}</td>
        </tr>    
    </table>