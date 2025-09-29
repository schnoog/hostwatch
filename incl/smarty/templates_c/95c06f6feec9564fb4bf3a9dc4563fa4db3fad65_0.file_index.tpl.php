<?php
/* Smarty version 5.5.2, created on 2025-09-29 18:26:09
  from 'file:index.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.2',
  'unifunc' => 'content_68dacf413d3167_37900759',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '95c06f6feec9564fb4bf3a9dc4563fa4db3fad65' => 
    array (
      0 => 'index.tpl',
      1 => 1759170366,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_68dacf413d3167_37900759 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/home/volker/Development/fritzphp/incl/smarty/templates';
?><html>
<head>
  <link rel="stylesheet" href="/ass/jquery-ui.css">
  <link rel="stylesheet" href="/ass/style.css">
  <?php echo '<script'; ?>
 src="/ass/jquery-3.7.1.js"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="/ass/jquery-ui.js"><?php echo '</script'; ?>
>

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
<?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('hosts'), 'host');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('host')->value) {
$foreach0DoElse = false;
?>
<tr>
<td  <?php echo $_smarty_tpl->getValue('host')['bgc'];?>
><?php echo $_smarty_tpl->getValue('host')['IPv4Address'];?>
</td>
<td <?php echo $_smarty_tpl->getValue('host')['bgc'];?>
><?php if ((strlen((string) $_smarty_tpl->getValue('host')['OwnName']) > 0)) {
echo $_smarty_tpl->getValue('host')['OwnName'];
} else { ?>H:<?php echo $_smarty_tpl->getValue('host')['HostName'];
}?> 
    <button type="button" onclick="ChangeOwnName(<?php echo $_smarty_tpl->getValue('host')['Idx'];?>
,'<?php if ((strlen((string) $_smarty_tpl->getValue('host')['OwnName']) > 0)) {
echo $_smarty_tpl->getValue('host')['OwnName'];
} else {
echo $_smarty_tpl->getValue('host')['HostName'];
}?>');">Change</button>
    
    
    
    </td>
<?php $_smarty_tpl->assign('sw', '<>', false, NULL);?>

<td><?php echo $_smarty_tpl->getValue('host')['HostName'];?>
</td>
<td><?php echo $_smarty_tpl->getValue('host')['FriendlyName'];?>
</td>
    <td <?php if (($_smarty_tpl->getValue('host')['TrackChanges'] > 0)) {?>bgcolor='lightgreen'<?php } else { ?>bgcolor='white'<?php }?> >

    <table><tr><td width="70%" style="text-align: left;">        
    <?php if (($_smarty_tpl->getValue('host')['TrackChanges'] == 0)) {?>No<?php } else { ?>Yes<?php }?>  
                </td><td width="20%" style="text-align: right;">  
    <button type="button" onclick="ToggleTrackChanges(<?php echo $_smarty_tpl->getValue('host')['Idx'];?>
);"><?php echo $_smarty_tpl->getValue('sw');?>
</button>
    </td></tr></table>
</td>
<td <?php if (($_smarty_tpl->getValue('host')['RequireOnline'] > 0)) {?>bgcolor='lightgreen'<?php } else { ?>bgcolor='white'<?php }?>>

    <table><tr><td width="70%" style="text-align: left;">
    <?php if (($_smarty_tpl->getValue('host')['RequireOnline'] == 0)) {?>No<?php } else { ?>Yes<?php }?>
        </td><td width="20%" style="text-align: right;">
    <button type="button" onclick="ToggleRequireOnline(<?php echo $_smarty_tpl->getValue('host')['Idx'];?>
);"><?php echo $_smarty_tpl->getValue('sw');?>
</button>
    </td></tr></table>

</td>

<td>

<?php if (($_smarty_tpl->getValue('host')['ParentHost'] != null)) {?>
     <?php echo $_smarty_tpl->getValue('parentdata')[$_smarty_tpl->getValue('host')['ParentHost']]['OwnName'];?>

<?php }?>
    <?php if (($_smarty_tpl->getValue('host')['ParentHost'] > -1)) {?>
    <button type="button" onclick="selectParent(<?php echo $_smarty_tpl->getValue('host')['Idx'];?>
,<?php echo $_smarty_tpl->getValue('host')['ParentHost'];?>
);"><?php echo $_smarty_tpl->getValue('sw');?>
</button>
    <?php } else { ?>
    <button type="button" onclick="selectParent(<?php echo $_smarty_tpl->getValue('host')['Idx'];?>
,null);"><?php echo $_smarty_tpl->getValue('sw');?>
</button>
    <?php }?>
</td>

</tr>
<?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>

</tbody>
</table>

<pre><?php echo $_smarty_tpl->getValue('dump');?>
</pre>






    
<?php echo '<script'; ?>
>

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

    <?php $_smarty_tpl->assign('abs', array(), false, NULL);?>
    <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('parentdata'), 'pd');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('pd')->value) {
$foreach1DoElse = false;
?>
        <?php if ($_smarty_tpl->getValue('pd')['Idx'] != $_smarty_tpl->getValue('idx')) {?> 
            <?php $_tmp_array = $_smarty_tpl->getValue('abs') ?? [];
if (!(is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess)) {
settype($_tmp_array, 'array');
}
$_tmp_array[] = array('value'=>$_smarty_tpl->getValue('pd')['Idx'],'label'=>$_smarty_tpl->getValue('pd')['OwnName']);
$_smarty_tpl->assign('abs', $_tmp_array, false, NULL);?>
        <?php }?>
    <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
    var Opts = [
    <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('abs'), 'opt', false, 'i');
$foreach2DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('i')->value => $_smarty_tpl->getVariable('opt')->value) {
$foreach2DoElse = false;
?>
        <?php if ($_smarty_tpl->getValue('i') > 0) {?>,<?php }?>{value:"<?php echo $_smarty_tpl->getValue('opt')['value'];?>
",label:"<?php echo $_smarty_tpl->getValue('opt')['label'];?>
"}
    <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
    ];


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






<?php echo '</script'; ?>
>



</body>
</html><?php }
}
