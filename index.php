<?php



require_once(__DIR__ . "/inc.php");




//QueryFritzBox();
//StateParse(); 
$CALLALL=false;
if(isset($_REQUEST['do'])){

    ProcessCall();
    $RenderNew = false;
}

if($RenderNew){
$hostdata = OutputStates($CALLALL);
$parentdata = GetParentList();

foreach($hostdata as $idx => $hostd ){
    $bgcolor = "";
    if($hostd['Active'] == 1){
        $bgcolor = "bgcolor='lightgreen'";
    }else{

        if($hostd['RequireOnline'] == 1){
            $bgcolor = "bgcolor='orange'";
        }
    }
    $hostdata[$idx]['bgc'] = $bgcolor;
}

$smarty->assign('hosts',$hostdata);
$smarty->assign('parentdata',$parentdata);
$smarty->assign('dump',print_r($hostdata,true));
$smarty->display('index.tpl');
}