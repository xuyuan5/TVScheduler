<?php

function schedule_recording($channel, $start, $end)
{
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        $sql = "INSERT INTO Schedules (Channel, StartTime, EndTime) VALUES(".'\''.$channel.'\''.", ".'\''.$start.'\''.", ".'\''.$end.'\''.")";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error inserting schedule ".mysql_error());
        }
        mysql_close($con);
    }
}

function unschedule_recording($channel, $start, $end)
{
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        $sql = "DELETE FROM Schedules WHERE Channel=".'\''.$channel.'\''." AND StartTime=".'\''.$start.'\''." AND EndTime=".'\''.$end.'\'';
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error removing schedule ".mysql_error());
        }
        mysql_close($con);
    }
}

function recording_is_scheduled($channel, $start, $end)
{
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        $sql = "SELECT * FROM Schedules WHERE Channel=".'\''.$channel.'\''." AND StartTime=".'\''.$start.'\''." AND EndTime=".'\''.$end.'\'';
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error finding desired schedule ".mysql_error());
        }
        if($result)
        {
            $num_rows = mysql_num_rows($result);
        }
        else
        {
            $num_rows = 0;
        }
        mysql_close($con);
        
        return $num_rows > 0;
    }
}

function get_recordings()
{
    $con = mysql_connect(SERVER_NAME, DBO_USER_NAME, DBO_PASSWORD);
    $all_recordings = array();
    if($con)
    {
        mysql_select_db(DATABASE_NAME, $con);
        // TODO-H: uncomment
        $sql = "SELECT * FROM Schedules";// WHERE NOW() <= EndTime";
        $result = mysql_query($sql, $con);
        if(!$result)
        {
            die("error listing schedule in specific time ".mysql_error());
        }
        while($row = mysql_fetch_array($result))
        {
            // TODO: convert datetime
            array_push($all_recordings, array('Channel' => $row['Channel'], 'StartTime' => $row['StartTime'], 'EndTime' => $row['EndTime']));
        }
        mysql_close($con);
    }
    
    return $all_recordings;
}

?>