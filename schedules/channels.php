<?php
/* 
 * @author Yuan Xu
*/

error_reporting(E_ALL);
//error_reporting(E_ERROR);
ini_set('display_errors', true);

if(!date_default_timezone_set("America/Toronto"))
{
	// TODO-L: print error
}

require_once('../config.php');
require_once('../database/channels.php');


$should_get_channels = true;
if(isset($_POST['action']))
{
    if($_POST['action'] == 'delete')
    {
        remove_channel($_POST['name']);
    }
    else if($_POST['action'] == 'update')
    {
        insert_or_update_channel($_POST['name'], $_POST['query'], $_POST['icon'], $_POST['number'], $_POST['tag'],
                                $_POST['programming'], $_POST['showtime'], $_POST['showtitle'], $_POST['episode'], 
                                $_POST['newepisode']);
        if(isset($_POST['queries']))
        {
            $queries = $_POST['queries'];
            $queryTypes = $_POST['querytypes'];
            $queryDefaults = $_POST['querydefaults'];
        }
        else
        {
            $queries = array();
            $queryTypes = array();
            $queryDefaults = array();
        }
        insert_or_update_queries($_POST['name'], $queries, $queryTypes, $queryDefaults);
        
        if(isset($_POST['regex']))
        {
            $regex = $_POST['regex'];
        }
        else
        {
            $regex = array();
        }
        insert_or_update_regex($_POST['name'], $regex);
    }
    else
    {
        print "please use proper interface to access this page.";
        $should_get_channels = false;
    }
}

if(isset($_GET['channel']))
{
    $should_get_channels = false;
    $channel_details = get_channel($_GET['channel']);
    echo json_encode($channel_details);
}

if($should_get_channels)
{
    $channels = get_channels();
    foreach($channels as $channel)
    {
?>
        <div class='channel'>
            <span class='channel-name'><?php echo $channel['Channel']; ?></span>
            <span class='channel-number'>(<?php echo $channel['ChannelNumber']; ?>)</span>
            <button class='channel-edit'>Edit</button>
            <button class='channel-delete'>Delete</button>
            <button class='channel-grab'>Grab Schedules</button>
        </div>
<?php
    }
}
?>