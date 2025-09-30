{literal}


async function selectTarget(idx, currTT = null, curr = null, targetid = null, currname = null) {
  var Msg = "Select new parent node";

  // Smarty fills Opts here...
{/literal}
    {assign var=abs value=[]}
    {foreach $tarettypes as $pd}

            {append var=abs value=['value'=>$pd.id,'label'=>$pd.targettype]}

    {/foreach}
    var Opts = [
    {foreach $abs as $i=>$opt}
        {if $i>0},{/if}{ldelim}value:"{$opt.value}",label:"{$opt.label}"{rdelim}
    {/foreach}
    ];
{literal}

  // Wait for user to choose
  const selectedValue = await changeTarget(Msg, Opts, currTT,curr, currname);

  if (selectedValue !== null) {
    const [tgtid, tgurl, tgname] = selectedValue.split("|");
    console.log("Targettye: " + tgtid);
    console.log("TUrl: " + tgurl);
    console.log("User picked:", selectedValue);
    
    if(currTT != null){
        // Edit
        UpdateTarget(targetid,tgtid,tgurl,tgname);        

    }else{

        AddTarget(idx,tgtid,tgurl,tgname );
        // new

    }



    //SetParent(idx,selectedValue);
    // do whatever with selectedValue
  } else {
    console.log("User cancelled");
  }
}





function changeTarget(message, options, defaultValue = null, defaultTarget = null, defaultName = null) {
  return new Promise((resolve) => {
    const modal = document.getElementById('targetPrompt');
    const msgEl = document.getElementById('targetPromptMessage');
    const selectEl = document.getElementById('targetPromptSelect');
    const textEl = document.getElementById('targetPromptText');
    const nameEl = document.getElementById('targetPromptName');
    const okBtn = document.getElementById('targetPromptOk');
    const cancelBtn = document.getElementById('targetPromptCancel');

    // Fill modal content
    msgEl.textContent = message;
    textEl.value = defaultTarget;
    nameEl.value = defaultName;


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
      resolve(selectEl.value + "|" + textEl.value + "|" + nameEl.value );
    };
    cancelBtn.onclick = () => {
      modal.style.display = 'none';
      resolve(null);
    };
  });
}

{/literal}