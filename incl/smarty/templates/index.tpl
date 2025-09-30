<html>
<head>
  <link rel="stylesheet" href="/ass/jquery-ui.css">
  <link rel="stylesheet" href="/ass/style.css">
  <script src="/ass/jquery-3.7.1.js"></script>
  <script src="/ass/jquery-ui.js"></script>

</head>

<body>
{include 'dialog.tpl'}



<div id="targetPrompt" style="
  position:fixed;
  top:0; left:0;
  width:100%; height:100%;
  background:rgba(0,0,0,0.5);
  display:none;
  justify-content:center;
  align-items:center;
">
  <div style="background:white; padding:20px; border-radius:8px; min-width:250px;">
    <div id="targetPromptMessage" style="margin-bottom:10px;"></div>
    <select id="targetPromptSelect" style="width:100%; margin-bottom:10px;"></select>
    <input type="text" id="targetPromptText" style="width:100%; margin-bottom:10px;"></input>
    <input type="text" id="targetPromptName" style="width:100%; margin-bottom:10px;"></input>    
    <div style="text-align:right;">
      <button id="targetPromptCancel">Cancel</button>
      <button id="targetPromptOk">OK</button>
    </div>
  </div>
</div>




<table border="1">
<thead>
<tr>
<th>IP</th><th>OwnName</th><th>HostName</th><th>FriendlyName</th><th>Track - Changes</th><th>Require - Online</th><th>Parent Host</th><th>Targets</th>
</tr>
</thead>
<tbody>
{$sw = '<>'}
{foreach $hosts as $host}
<tr>
<td  {$host.bgc}>{$host.IPv4Address}</td>
<td {$host.bgc}>{if (strlen($host.OwnName)> 0)}{$host.OwnName}{else}H:{$host.HostName}{/if} 
    <button type="button" onclick="ChangeOwnName({$host.Idx},'{if (strlen($host.OwnName)> 0)}{$host.OwnName}{else}{$host.HostName}{/if}');">Change</button>
</td>

<td>{$host.HostName}</td>
<td>{$host.FriendlyName}</td>
<td {if ($host.TrackChanges > 0)}bgcolor='lightgreen'{else}bgcolor='white'{/if} >
    <table>
        <tr>
            <td width="70%" style="text-align: left;">{if ($host.TrackChanges == 0)}No{else}Yes{/if}  
            </td><td width="20%" style="text-align: right;"><button type="button" onclick="ToggleTrackChanges({$host.Idx});">{$sw}</button>
            </td>
        </tr>
    </table>
</td>
<td {if ($host.RequireOnline > 0)}bgcolor='lightgreen'{else}bgcolor='white'{/if}>
    <table>
        <tr>
            <td width="70%" style="text-align: left;">{if ($host.RequireOnline == 0)}No{else}Yes{/if}
            </td><td width="20%" style="text-align: right;"><button type="button" onclick="ToggleRequireOnline({$host.Idx});">{$sw}</button>
            </td>
        </tr>
    </table>
</td>
<td>

{if ($host.ParentHost != null)}
     {$parentlist[$host.ParentHost].OwnName}
{/if}
    {if ($host.ParentHost > -1)}
    <button type="button" onclick="selectParent({$host.Idx},{$host.ParentHost});">{$sw}</button>
    {else}
    <button type="button" onclick="selectParent({$host.Idx},null);">{$sw}</button>
    {/if}
</td>

<td>
    <table><tr><td>
    <button type="button" onclick='selectTarget("{$host.Idx}" );'>+</button>
    </td><td>
        <table>
        {if (isset($targets[$host.Idx]))}
            {foreach $targets[$host.Idx] as $tg}
                {if ($tg.targettype == "web")}

                    <tr><td>
                    <a href="{$tg.target}" target="_blank">{$tg.targetname}</a>
                    </td><td>
                    <button type="button" onclick='selectTarget("{$host.Idx}", "{$tg.targettypid}" , "{$tg.target}", "{$tg.tid}", "{$tg.targetname}" );'>&#9999;</button>
                    <button type="button" onclick='deleteTarget("{$tg.tid}");'>&#x2421;</button>
                    </td></tr> 
                {/if}
            {/foreach}
        {/if}
        </table>
    </td></tr></table>



</td>

</tr>
{/foreach}

</tbody>
</table>

<pre>{$dump}</pre>





    
<script>
{include 'js_base.tpl'}
{include 'js_parent.tpl'}
{include 'js_target.tpl'}

{literal}

function deleteTarget(targetid){
    if (confirm("Delete this target?") == true) {
        SendRequest(targetid,"deleteTarget");
    }
}


function ChangeOwnName(idx,oldname){
    newname = prompt("Neuer Name",oldname);
    if(newname != null){
        SendRequest(idx,"OwnName",newname);
    }
}

function ToggleTrackChanges(idx){
   
    SendRequest(idx,"ToggleTrackChanges",0);
}

function ToggleRequireOnline(idx){
    SendRequest(idx,"ToggleRequireOnline",0);
}

function SetParent(idx,parentidx){
    SendRequest(idx,"SetParent",parentidx);

}


function  UpdateTarget(targetid,targettypid,target,targetname){
    var tgdata = targettypid + "|" + target + "|" + targetname;
    SendRequest(targetid,"UpdateTarget",tgdata);
}

function AddTarget(idx,targettypid,target,targetname){
    var tgdata = targettypid + "|" + target + "|" + targetname;
    SendRequest(idx,"AddTarget",tgdata);

}











</script>
{/literal}


</body>
</html>