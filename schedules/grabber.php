<?php
/* 
 * @author Yuan Xu
*/

require_once('../helper/common_header.php');
require_once('../helper/utilities.php');

require_once('../config.php');
require_once('../database/channels.php');
require_once('../lib/date_time.php');
require_once('../lib/web.php');

if(isset($_POST['target']))
{
    if($_POST['target'] == 'all')
    {
        $targets = array();
        $channels = get_channels();
        foreach($channels as $channel)
        {
            array_push($targets, $channel['Channel']);
        }
    }
    else
    {
        $targets = explode(',', $_POST['target']);
    }

    foreach($targets as $channel)
    {
        grab_schedule(get_channel($channel));
    }
}

function grab_schedule($details)
{
    $url = generate_url($details['query'], $details['queries'], get_date_in_mills());
    $doc = new DOMDocument();
    $html = get_url_contents($url);
    
    filter_html($details['regex'], $html);
    $doc->loadHTML($html);
    
    $xpath = new DOMXPath($doc);
    $schedule = $doc->getElementById($details['tag']);
    $programmings = $xpath->evaluate("descendant::*[@class='".$details['programming']."']", $schedule);
    $curr_time = add_date_timestamp(time(), -60, 0, -1, 0);
    $sql = array();
    foreach($programmings as $programming)
    {
        $time = $xpath->evaluate("descendant::*[@class='".$details['showtime']."']", $programming);
        if($time->length != 1)
        {
            die("expected only one show time.");
        }
        $new_time = merge_date_and_time($curr_time, strtotime($time->item(0)->textContent));
        if($new_time < $curr_time)
        {
            $new_time = add_date_timestamp($new_time);
        }
        $curr_time = $new_time;
        
        $title = $xpath->evaluate("descendant::*[@class='".$details['showtitle']."']", $programming);
        if($title->length != 1)
        {
            die("expected only one title.");
        }
        
        $ep = $xpath->evaluate("descendant::*[@class='".$details['episode']."']", $programming);
        if($ep->length > 1)
        {
            die("expected at most one episode name.");
        }
        
        $new_ep = $xpath->evaluate("descendant::*[@class='".$details['newepisode']."']", $programming);
        if($new_ep->length > 1)
        {
            die("expected at most one new episode flag.");
        }
        array_push($sql, prepare_insert_timetable($details['name'], $title->item(0)->textContent, $curr_time, 
                                                  $ep->length == 0? "" : $ep->item(0)->textContent, 
                                                  $new_ep->length == 0? 0 : 1));
    }
    insert_to_timetable($details['name'], $sql);
}

function generate_url($url, $queries, $startdate)
{
    $url .= '?';
    foreach($queries as $query)
    {
        $url .= $query['name'].'=';
        if($query['type'] == 'date-ms')
        {
            $url .= $startdate;
        }
        else
        {
            $url .= $query['defaultValue'];
        }
        $url .= '&';
    }
    return trim($url, '&');
}

function get_date_in_mills()
{
    $curr_date = getdate();
    return "".mktime(0, 0, 0, $curr_date['mon'], $curr_date['mday'], $curr_date['year'], -1).'000';
}

function filter_html($regex, &$html)
{
    $html = preg_replace($regex, "", $html);
    $html = preg_replace("{& }s", "&amp; ", $html);
}
?>