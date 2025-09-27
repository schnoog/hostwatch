<?php


function OutputStates(){
    $data = DB::query('Select * from hosts WHERE Active = 1 or Trackchanges = 1');

    print_r($data);


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
