{literal}

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


{/literal}