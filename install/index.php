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

require_once('../template/empty_header.php');
if(file_exists('../config.php'))
{
    if(isset($_GET['action']) && $_GET['action'] == 'upgrade')
    {
        require_once('content.php');
    }
    else
    {
?>
        <p>Installation is already done! Do you wish to recreate database? <a href='?action=upgrade'>Yes</a></p>
<?php
    }
} else {
	require_once('content.php');
}
require_once('../template/footer.php');
?>
