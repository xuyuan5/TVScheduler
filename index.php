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
require_once('template/empty_header.php');
?>
	<h1>Please run <a href="
	<?php 
		$pageURL = 'http';
		if(array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on') { $pageURL .= 's'; }
		$pageURL .='://';
		if($_SERVER['SERVER_PORT'] != '80') {
			$pageURL .= $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
		} else {
			$pageURL .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		}
		echo substr($pageURL, 0, strrpos($pageURL, '/')+1); 
	?>
	install">Install</a> before visiting again.</h1>
<?php
}
else
{
require_once('template/header.php');
?>
<ul class='navigation'>
    <li><a href='?action=display'>Show Schedule</a></li>
    <li><a href='?action=grab'>Grab Schedule</a></li>
</ul>

<?php
    if(isset($_GET['action']))
    {
        $action = $_GET['action'];

        if( $action == 'grab' )
        {
            require_once('pages/grab.php');
        }
        else
        {
            require_once('pages/content.php');
        }
    }
}
require_once('template/footer.php');
?>
