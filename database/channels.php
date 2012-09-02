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

function get_channels()
{
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    $all_channels = array();
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        $sql = "SELECT ChannelNumber, Channel, IconURL, QueryURL FROM Channels";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error gathering all channels ".mysql_error());
        }
        while($row = mysql_fetch_array($result))
        {
            array_push($all_channels, array('Channel' => $row['Channel'], 
                                            'ChannelNumber' => $row['ChannelNumber'],
                                            'IconURL' => $row['IconURL'],
                                            'QueryURL' => $row['QueryURL']));
        }
        mysql_close($con);
    }
    
    return $all_channels;
}

function get_channel($channel)
{
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    $details = array();
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        $sql = "SELECT * FROM Channels WHERE Channel='".$channel."'";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error getting channel details for channel ".$channel." ".mysql_error());
        }
        
        // Pre-Condition: while loop shouldn't loop back
        while($row = mysql_fetch_array($result))
        {
            $details['name'] = $row['Channel'];
            $details['number'] = $row['ChannelNumber'];
            $details['icon'] = $row['IconURL'];
            $details['query'] = $row['QueryURL'];
            $details['tag'] = $row['Tag'];
            $details['programming'] = $row['ProgrammingClassName'];
            $details['showtime'] = $row['ProgrammingTimeClassName'];
            $details['showtitle'] = $row['ProgrammingNameClassName'];
            $details['episode'] = $row['ProgrammingEpClassName'];
            $details['newepisode'] = $row['ProgrammingNewEpClassName'];
        }
        
        // grab queries
        $sql = "SELECT * FROM Queries WHERE Channel='".$channel."'";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error getting query details for channel ".$channel." ".mysql_error());
        }
        
        $queries = array();
        while($row = mysql_fetch_array($result))
        {
            array_push($queries, array('name' => $row['Name'], 'type' => $row['Type'], 'defaultValue' => $row['DefaultValue']));
        }
        $details['queries'] = $queries;
        
        // grab regex
        $sql = "SELECT * FROM IgnoreRegEx WHERE Channel='".$channel."'";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error getting regex details for channel ".$channel." ".mysql_error());
        }
        
        $regex = array();
        while($row = mysql_fetch_array($result))
        {
            array_push($regex, $row['RegEx']);
        }
        $details['regex'] = $regex;
        
        mysql_close($con);
    }
    
    return $details;
}

function remove_channel($channel)
{
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        
        $sql = "DELETE FROM Queries WHERE Channel=".'\''.$channel.'\'';
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error removing queries with channel ".$channel." ".mysql_error());
        }
        
        $sql = "DELETE FROM IgnoreRegEx WHERE Channel=".'\''.$channel.'\'';
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error removing regex with channel ".$channel." ".mysql_error());
        }
        
        $sql = "DELETE FROM Channels WHERE Channel=".'\''.$channel.'\'';
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error removing channel ".mysql_error());
        }
        mysql_close($con);
    }
}

function insert_or_update_channel($channel, $qURL, $iURL, $cNum, $tag, $pcn, $ptcn, $pncn, $pecn, $pnecn)
{
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        
        $sql = "INSERT INTO Channels 
        (Channel, IconURL, QueryURL, ChannelNumber, Tag, ProgrammingClassName, 
        ProgrammingTimeClassName, ProgrammingNameClassName, ProgrammingEpClassName, ProgrammingNewEpClassName) 
        VALUES('".$channel."', '".$iURL."', '".$qURL."', '".$cNum."', '".$tag."', '".$pcn."', '".$ptcn.
        "', '".$pncn."', '".$pecn."', '".$pnecn."') ON DUPLICATE KEY UPDATE IconURL='".$iURL."', QueryURL='".
        $qURL."', Tag='".$tag."', ProgrammingClassName='".$pcn."', ProgrammingTimeClassName='".$ptcn.
        "', ProgrammingNameClassName='".$pncn."', ProgrammingEpClassName='".$pecn."', ProgrammingNewEpClassName='".$pnecn."'";
        
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error inserting channel ".mysql_error());
        }
        mysql_close($con);
    }
}

function insert_or_update_queries($channel, $queries, $queryTypes, $queryDefaults)
{
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        
        $sql = "DELETE FROM Queries WHERE Channel='".$channel."'";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error removing queries with channel ".$channel." ".mysql_error());
        }
        
        $count = count($queries);
        for ($i = 0; $i < $count; $i++)
        {
            $sql = "INSERT INTO Queries (Channel, Name, Type, DefaultValue) 
            VALUES('".$channel."', '".$queries[$i]."', '".$queryTypes[$i]."', '".$queryDefaults[$i]."')";
            $result = mysql_query($sql, $con);
            if(!$result)
            {
                die("error inserting query for channel ".$channel." ".mysql_error());
            }
        }
        mysql_close($con);
    }
}

function insert_or_update_regex($channel, $regex)
{
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        
        $sql = "DELETE FROM IgnoreRegEx WHERE Channel='".$channel."'";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error removing regex with channel ".$channel." ".mysql_error());
        }
        
        $count = count($regex);
        for ($i = 0; $i < $count; $i++)
        {
            $sql = "INSERT INTO IgnoreRegEx (Channel, RegEx) VALUES('".$channel."', '".$regex[$i]."')";
            $result = mysql_query($sql, $con);
            if(!$result)
            {
                die("error inserting regex for channel ".$channel." ".mysql_error());
            }
        }
        mysql_close($con);
    }
}

function prepare_insert_timetable($channel, $title, $time, $episode, $is_new_ep)
{
    $title = str_replace("'", "\\'", $title);
    $episode = str_replace("'", "\\'", $episode);
    $sql = "INSERT INTO TimeTable (Channel, Title, StartTime, Episode, IsNewEpisode)
           VALUES('".$channel."', '".$title."', ".$time.", '".$episode."', ".$is_new_ep.")";
    return $sql;
}

function insert_to_timetable($channel, $sql_array)
{
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        
        $sql = "DELETE FROM TimeTable WHERE Channel='".$channel."'";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error removing old schedules from TimeTable for channel ".$channel." ".mysql_error());
        }
        
        foreach($sql_array as $sql_stmt)
        {
            $result = mysql_query($sql_stmt, $con);
            if(!$result)
            {
                die("error inserting schedules to TimeTable for channel ".$channel." ".mysql_error());
            }
        }
        mysql_close($con);
    }
}

function get_timetable_for_channel($channel)
{
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    $timetable = array();
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        $sql = "SELECT Title, StartTime, Episode, IsNewEpisode FROM TimeTable WHERE Channel='".$channel."'";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error gathering all timetable information for channel ".$channel." ".mysql_error());
        }
        while($row = mysql_fetch_array($result))
        {
            array_push($timetable, array('title' => $row['Title'], 
                                         'start' => $row['StartTime'],
                                         'sub-title' => $row['Episode'],
                                         'is-new' => $row['IsNewEpisode']));
        }
        mysql_close($con);
    }
    
    return $timetable;
}
?>