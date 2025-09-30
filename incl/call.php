<?php

function ProcessCall(){
//Array ( [do] => 1 [req] => OwnName [idx] => 120 [val] => volker-PC ) 
    echo json_encode($_REQUEST) . PHP_EOL;

    $idx = $_REQUEST['idx'];
    switch ($_REQUEST['req']) {
        case "OwnName":
            $newname = $_REQUEST['val'];
            DB::query("Update hosts SET OwnName = %s WHERE Idx = %i",$newname,$idx);

            break;
        case "ToggleTrackChanges":
            ToggleField($idx,'TrackChanges');
            break;
        case "ToggleRequireOnline":
            ToggleField($idx,"RequireOnline");
            break;
        case "SetParent":
            SetParent($idx,$_REQUEST['val']);
            break;
        case "UpdateTarget":
            $targetid = $idx;
            list($targettype,$target,$targetname) = explode("|",$_REQUEST['val'],3);
            UpdateTarget($targetid,$targettype,$target,$targetname);
            break;
        case "AddTarget":
            list($targettype,$target,$targetname) = explode("|",$_REQUEST['val'],3);
            AddTarget($idx,$targettype,$target,$targetname);
            break;
        case "deleteTarget":
            $targetid = $idx;
            DeleteTarget($targetid);            
            break;


        default:
            echo "unknown input";
    }    

}

/*

    SendRequest(targerid,"UpdateTarget",tgdata);
}

function AddTarget(idx,targettypid,target){
    var tgdata = targettypid + "|" + target;
    SendRequest(idx,"AddTarget",tgdata);

*/


function ToggleField($idx,$fieldname){
    $newval = 1;
    $oldval = DB::queryFirstField("Select ". $fieldname ." from hosts WHERE Idx = %i",$idx);
    if($oldval == 1) $newval = 0;

    DB::query( 'Update hosts SET ' . $fieldname . ' = %i WHERE Idx = %i',$newval,$idx);


}