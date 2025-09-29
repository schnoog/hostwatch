<html>
<head>
  <link rel="stylesheet" href="/ass/jquery-ui.css">
  <link rel="stylesheet" href="/ass/style.css">
  <script src="/ass/jquery-3.7.1.js"></script>
  <script src="/ass/jquery-ui.js"></script>

</head>

<body>
<div id="customPrompt" style="
  position:fixed;
  top:0; left:0;
  width:100%; height:100%;
  background:rgba(0,0,0,0.5);
  display:none;
  justify-content:center;
  align-items:center;
">
  <div style="background:white; padding:20px; border-radius:8px; min-width:250px;">
    <div id="customPromptMessage" style="margin-bottom:10px;"></div>
    <select id="customPromptSelect" style="width:100%; margin-bottom:10px;"></select>
    <div style="text-align:right;">
      <button id="customPromptCancel">Cancel</button>
      <button id="customPromptOk">OK</button>
    </div>
  </div>
</div>


<table border="1">
<thead>
<tr>
<th>IP</th><th>OwnName</th><th>HostName</th><th>FriendlyName</th><th>Track - Changes</th><th>Require - Online</th><th>Parent Host</th>
</tr>
</thead>
<tbody>
{foreach $hosts as $host}
<tr>
<td  {$host.bgc}>{$host.IPv4Address}</td>
<td {$host.bgc}>{if (strlen($host.OwnName)> 0)}{$host.OwnName}{else}H:{$host.HostName}{/if} 
    <button type="button" onclick="ChangeOwnName({$host.Idx},'{if (strlen($host.OwnName)> 0)}{$host.OwnName}{else}{$host.HostName}{/if}');">Change</button>
    
    
    
    </td>
{$sw = '<>'}

<td>{$host.HostName}</td>
<td>{$host.FriendlyName}</td>
    <td {if ($host.TrackChanges > 0)}bgcolor='lightgreen'{else}bgcolor='white'{/if} >

    <table><tr><td width="70%" style="text-align: left;">        
    {if ($host.TrackChanges == 0)}No{else}Yes{/if}  
                </td><td width="20%" style="text-align: right;">  
    <button type="button" onclick="ToggleTrackChanges({$host.Idx});">{$sw}</button>
    </td></tr></table>
</td>
<td {if ($host.RequireOnline > 0)}bgcolor='lightgreen'{else}bgcolor='white'{/if}>

    <table><tr><td width="70%" style="text-align: left;">
    {if ($host.RequireOnline == 0)}No{else}Yes{/if}
        </td><td width="20%" style="text-align: right;">
    <button type="button" onclick="ToggleRequireOnline({$host.Idx});">{$sw}</button>
    </td></tr></table>

</td>

<td>

{if ($host.ParentHost != null)}
     {$parentdata[$host.ParentHost].OwnName}
{/if}
    {if ($host.ParentHost > -1)}
    <button type="button" onclick="selectParent({$host.Idx},{$host.ParentHost});">{$sw}</button>
    {else}
    <button type="button" onclick="selectParent({$host.Idx},null);">{$sw}</button>
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
   
    SendRequest(idx,"ToggleTrackChanges",0);
}

function ToggleRequireOnline(idx){
    SendRequest(idx,"ToggleRequireOnline",0);
}

function SetParent(idx,parentidx){
    SendRequest(idx,"SetParent",parentidx);

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

            console.log("ANSWER:" + xhr.responseText);
            location.reload();            
        } else {
            console.log(`Error: ${xhr.status}`);
        }
        };
}


async function selectParent(idx, curr = null) {
  var Msg = "Select new parent node";

  // Smarty fills Opts here...
{/literal}
    {assign var=abs value=[]}
    {foreach $parentdata as $pd}
        {if $pd.Idx != $idx} 
            {append var=abs value=['value'=>$pd.Idx,'label'=>$pd.OwnName]}
        {/if}
    {/foreach}
    var Opts = [
    {foreach $abs as $i=>$opt}
        {if $i>0},{/if}{ldelim}value:"{$opt.value}",label:"{$opt.label}"{rdelim}
    {/foreach}
    ];
{literal}

  // Wait for user to choose
  const selectedValue = await selectPrompt(Msg, Opts, curr);

  if (selectedValue !== null) {
    console.log("User picked:", selectedValue);
    SetParent(idx,selectedValue);
    // do whatever with selectedValue
  } else {
    console.log("User cancelled");
  }
}


function selectPrompt(message, options, defaultValue = null) {
  return new Promise((resolve) => {
    const modal = document.getElementById('customPrompt');
    const msgEl = document.getElementById('customPromptMessage');
    const selectEl = document.getElementById('customPromptSelect');
    const okBtn = document.getElementById('customPromptOk');
    const cancelBtn = document.getElementById('customPromptCancel');

    // Fill modal content
    msgEl.textContent = message;
    selectEl.innerHTML = '';

    options.forEach(opt => {
      const o = document.createElement('option');
      o.value = opt.value ?? opt;
      o.textContent = opt.label ?? opt;

      // Mark as selected if this matches defaultValue
      if (defaultValue !== null && o.value == defaultValue) {
        o.selected = true;
      }

      selectEl.appendChild(o);
    });

    // If no option matched but you still want a default, you can set it here:
    if (defaultValue !== null) {
      selectEl.value = defaultValue;
    }

    // Show modal
    modal.style.display = 'flex';

    // Clean up old listeners
    okBtn.onclick = cancelBtn.onclick = null;

    okBtn.onclick = () => {
      modal.style.display = 'none';
      resolve(selectEl.value);
    };
    cancelBtn.onclick = () => {
      modal.style.display = 'none';
      resolve(null);
    };
  });
}

function selectPromptX(message, options) {
  return new Promise((resolve) => {
    const modal = document.getElementById('customPrompt');
    const msgEl = document.getElementById('customPromptMessage');
    const selectEl = document.getElementById('customPromptSelect');
    const okBtn = document.getElementById('customPromptOk');
    const cancelBtn = document.getElementById('customPromptCancel');

    // Fill modal content
    msgEl.textContent = message;
    selectEl.innerHTML = '';
    options.forEach(opt => {
      const o = document.createElement('option');
      o.value = opt.value ?? opt;
      o.textContent = opt.label ?? opt;
      selectEl.appendChild(o);
    });

    // Show modal
    modal.style.display = 'flex';

    // Clean up old listeners
    okBtn.onclick = cancelBtn.onclick = null;

    okBtn.onclick = () => {
      modal.style.display = 'none';
      resolve(selectEl.value);
    };
    cancelBtn.onclick = () => {
      modal.style.display = 'none';
      resolve(null);
    };
  });
}






</script>
{/literal}


</body>
</html>