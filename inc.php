<?php

$SBD = __DIR__ . "/";
$INCD = $SBD . "incl/";


require_once($SBD. "vendor/autoload.php");





$includes = [
    "host_class.php",
    "functions.php",
    "hoststate.php",
    "fbwork.php",
    "config.php"
];

foreach($includes as $include){
    require_once($INCD . $include);

}

    
    
    
    


DB::$dsn = 'mysql:host='.$CONFIG['db']['host'].';dbname=' . $CONFIG['db']['db'];
DB::$user = $CONFIG['db']['user'];
DB::$password = $CONFIG['db']['pass'];


