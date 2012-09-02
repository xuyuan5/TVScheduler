<?php
function add_date_timestamp($original, $min=0, $hr=0, $day=1, $month=0)
{
    return mktime(date('H',$original)+$hr, 
                  date('i',$original)+$min, 
                  date('s',$original), 
                  date('m',$original)+$month, 
                  date('d',$original)+$day, 
                  date('Y',$original));
}

function merge_date_and_time($date, $time)
{
    return mktime(date('H', $time), date('i', $time), date('s', $time),
                  date('m', $date), date('d', $date), date('Y', $date));
}

function elapsed_time_of_day($date)
{
    $start_of_day = mktime(date('H', 0), date('i', 0), date('s', 0),
                    date('m', $date), date('d', $date), date('Y', $date));
    return $date - $start_of_day;
}

function remaining_time_of_day($date)
{
    $total = 24*60*60;
    return $total - elapsed_time_of_day($date);
}
?>
