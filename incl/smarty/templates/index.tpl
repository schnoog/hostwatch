<html>
<head>
  <link rel="stylesheet" href="/ass/jquery-ui.css">
  <link rel="stylesheet" href="/ass/style.css">
  <script src="/ass/jquery-3.7.1.js"></script>
  <script src="/ass/jquery-ui.js"></script>

</head>

<body>


<table border="1">
<thead>
<tr>
<th>IP</th><th>OwnName</th><th>HostName</th><th>FriendlyName</th><th>Track - Changes</th><th>Require - Online</th><th>Parent</th>
</tr>
</thead>
<tbody>
{foreach $hosts as $host}
<tr {$host.bgc}>
<td>{$host.IPv4Address}</td>
    <td>{if (strlen($host.OwnName)> 0)}{$host.OwnName}{else}H:{$host.HostName}{/if} 
    <button type="button" onclick="ChangeOwnName({$host.Idx},'{if (strlen($host.OwnName)> 0)}{$host.OwnName}{else}{$host.HostName}{/if}');">Change</button>
    
    
    
    </td>
{$sw = '<>'}

<td>{$host.HostName}</td>
<td>{$host.FriendlyName}</td>
    <td {if ($host.TrackChanges > 0)}bgcolor='lightgreen'{else}bgcolor='white'{/if} >

    <table><tr><td width="70%" style="text-align: left;">        
    {if ($host.TrackChanges == 0)}No{else}Yes{/if}  
                </td><td width="20%" style="text-align: right;">  
    <button type="button" onclick="ToggleTrackChanges({$host.Idx});">{if ($host.TrackChanges == 0)}{$sw}{else}{$sw}{/if}</button>
    </td></tr></table>
</td>
<td {if ($host.RequireOnline > 0)}bgcolor='lightgreen'{else}bgcolor='white'{/if}>

    <table><tr><td width="70%" style="text-align: left;">
    {if ($host.RequireOnline == 0)}No{else}Yes{/if}
        </td><td width="20%" style="text-align: right;">
    <button type="button" onclick="ToggleRequireOnline({$host.Idx});">{if ($host.RequireOnline == 0)}{$sw}{else}{$sw}{/if}</button>
    </td></tr></table>

</td>

<td>

{if ($host.ParentHost != null)}
     {$parentdata[$host.ParentHost].OwnName}
{/if}

</td>

</tr>
{/foreach}

</tbody>
</table>

<pre>{$dump}</pre>





{literal}
    
<script>

function ChangeOwnName(idx,oldname){
    newname = prompt("Neuer Name",oldname);
    if(newname != null){
        SendRequest(idx,"OwnName",newname);
    }
}

function ToggleTrackChanges(idx){
    alert("TTC: " + idx);
    SendRequest(idx,"ToggleTrackChanges",0);
}

function ToggleRequireOnline(idx){
    SendRequest(idx,"ToggleRequireOnline",0);
}


function SendRequest(idx,req,val){


        const xhr = new XMLHttpRequest();

        const params = new URLSearchParams();
        params.append("do", 1);
        params.append("req", req);
        params.append("idx", idx);
        params.append("val", val);
        xhr.open("POST", "index.php?do=1");
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(params.toString());
        
        xhr.onload = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            location.reload();
            console.log("ANSWER:" + JSON.parse(xhr.responseText));
        } else {
            console.log(`Error: ${xhr.status}`);
        }
        };





}



</script>
{/literal}}


</body>
</html>