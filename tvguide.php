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
if(!file_exists('config.php'))
{
    die("ERROR: database is not setup correctly");
}

require_once('config.php');
require_once('database/channels.php');
?>
<!DOCTYPE xmlTV SYSTEM "xmltv.dtd">
<tv generator-info-name="TV Schedule ©Coypright 2011-2012 Yuan Xu" generator-info-url="http://xuyuan.dyndns-free.com">
<?php
// generate channels
$channels = get_channels();
foreach($channels as $channel)
{
?>
    <channel id="<?php echo $channel['Channel']; ?>">
        <display-name><?php echo $channel['Channel']; ?></display-name>
        <icon><?php echo $channel['IconURL']; ?></icon>
        <url><?php echo $channel['QueryURL']; ?></url>
    </channel>
<?php
}
// generate programmes
foreach($channels as $channel)
{
    $timetable = get_timetable_for_channel($channel['Channel']);
    foreach($timetable as $programming)
    {
?>
    <programme start="<?php echo date('YmdHis T', $programming['start']); ?>" channel="<?php echo $channel['Channel']; ?>" is-new="<?php echo $programming['is-new']?'True':'False'; ?>">
        <title><?php echo $programming['title']; ?></title>
        <sub-title><?php echo $programming['sub-title']; ?></sub-title>
    </programme>
<?php
    }
}
?>
</tv>