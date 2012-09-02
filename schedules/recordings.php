<?php

error_reporting(E_ALL);
//error_reporting(E_ERROR);
ini_set('display_errors', true);

if(!date_default_timezone_set("America/Toronto"))
{
    // TODO-L: print error
}

require_once('../config.php');
require('../database/recordings.php');

$recordings = get_recordings();

echo json_encode($recordings);
?>