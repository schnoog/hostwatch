<?php

$SBD = __DIR__ . "/";
$INCD = $SBD . "incl/";


require_once($SBD. "vendor/autoload.php");
use Smarty\Smarty;


$RenderNew = true;


$includes = [
    "host_class.php",
    "functions.php",
    "hoststate.php",
    "fbwork.php",
    "config.php",
    "call.php"
];

foreach($includes as $include){
    require_once($INCD . $include);

}


$smarty = new Smarty();

$smarty->setTemplateDir($INCD .'smarty/templates');
$smarty->setCompileDir($INCD .'smarty/templates_c');
$smarty->setCacheDir($INCD .'smarty/cache');
$smarty->setConfigDir($INCD .'smarty/configs');

    
    


DB::$dsn = 'mysql:host='.$CONFIG['db']['host'].';dbname=' . $CONFIG['db']['db'];
DB::$user = $CONFIG['db']['user'];
DB::$password = $CONFIG['db']['pass'];


