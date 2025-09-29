<?php

function SetParent($idx,$parentidx){
    if($parentidx < 0) $parentidx = null;
    DB::query('Update hosts SET ParentHost = %? WHERE Idx = %i',$parentidx,$idx);
}


function GetParentList($full = false){
    $ret = array();
    if($full){
        $res = DB::query("Select Idx, OwnName, HostName, IPv4Address from hosts");
    }else{
        $res = DB::query("Select Idx, OwnName, HostName, IPv4Address from hosts WHERE TrackChanges = 1");
    }
    $ret[-1] =  ['OwnName' => "null", 'Idx' => -1];    
    for($x=0;$x<count($res);$x++){

        $ret[ $res[$x]['Idx']  ] = $res[$x];

        if(strlen($ret[ $res[$x]['Idx']  ]['OwnName'] )< 3){
            $ret[ $res[$x]['Idx']  ]['OwnName'] = $ret[ $res[$x]['Idx']  ]['HostName'];
        }


    }

    return $ret;
}



function OutputStates($all = false){
    $ret = array();

    $sql = 'Select * from hosts WHERE (Active = 1 or TrackChanges = 1) AND HideHost = 0 ORDER BY INET_ATON(IPv4Address)';
    if($all) $sql = 'Select * from hosts WHERE HideHost = 0 ORDER BY INET_ATON(IPv4Address)';


    $data = DB::query($sql);
    for($x=0;$x < count($data);$x++){

            $ret[ $data[$x]['Idx']] = $data[$x]  ;

    }
    return $ret;
}



function GetLastHostsState(){
    $ret =  array();
    $sql = "SELECT s.* 
    FROM statechange AS s 
    INNER JOIN (
        SELECT idx, MAX(`timestamp`) AS max_ts 
        FROM statechange 
        GROUP BY idx
    ) AS m 
    ON s.idx = m.idx AND s.`timestamp` = m.max_ts";

    $res = DB::query($sql);
    for($x=0;$x < count($res);$x++){
        $ret[ $res[$x]['idx'] ] = $res[$x]['state'];
    }
    return $ret;
}



function StateParse(){
    $stateCheckStart = time();
    $debugchange = array();
    $lastState = GetLastHostsState();
    $hosts = Host::loadAll();
    $updateData = [];
    foreach($hosts as $ht){
        if($ht->getTrackChanges() > 0){
            $idx = $ht->getIndex();
            $ls = $ht->getActive();
            $upd = true;
            if(isset($lastState[$idx])){
                if($lastState[$idx] == $ls) $upd = false;
            }
            if($upd){
                $rec = [
                    'idx' => $idx,
                    'state' => $ls,
                    'timestamp' => $stateCheckStart,

                ];
                $debugchange[] = $ht->outputHost();
                $updateData[] = $rec;
            }
        }
    }
    if(count($updateData)> 0){
        echo "Adding " .  count($updateData) . " state changes" . PHP_EOL;
        print_r($debugchange);
        print_r($updateData);
        DB::insert('statechange',$updateData);
    }

}



function GetTargetsFull(){
    $res = DB::query('SELECT t.*, h.*, t2.* FROM hostwatch.targets t 	JOIN hostwatch.hosts h ON t.hostid = h.Idx 	JOIN hostwatch.targettypes t2 ON t.targettypid = t2.id');
    $ret = array();
    for($x=0;$x < count($res);$x++){
        $line = $res[$x];
        $ret[$line['hostid']][] = $line;
    }
    return $ret;   
}

function GetTargetsList(){
    $res = DB::query('SELECT t.*, t2.* FROM hostwatch.targets t 	JOIN hostwatch.targettypes t2 ON t.targettypid = t2.id');
    $ret = array();
    for($x=0;$x < count($res);$x++){
        $line = $res[$x];
        $ret[$line['hostid']][] = $line;
    }
    return $ret;   
}