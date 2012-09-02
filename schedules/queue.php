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

$channel = $_GET['channel'];
$action = $_GET['action'];
$start = $_GET['start'];
$end = $_GET['end'];

$start_string = date('Y-m-d H:i:s', $start);
$end_string = date('Y-m-d H:i:s', $end);

if($action == 'update')
{
	if(recording_is_scheduled($channel, $start_string, $end_string))
	{
		print "true";
	}
	else
	{
		print "false";
	}
} 
else if($action == 'queue')
{
	schedule_recording($channel, $start_string, $end_string);
} 
else if($action == 'dequeue')
{
	unschedule_recording($channel, $start_string, $end_string);
} 
else
{
	// TODO-L: error, do something
}
?>
