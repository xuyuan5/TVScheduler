<?php
/* 
 * precondition: tvguide.xml is present in local path
*/

$channels = array();
$earliest_date = new Date((date('Y') + 1).'0000');
$latest_date = new Date((date('Y')-1).'0000');

function get_schedule_start_date()
{
	global $earliest_date;
	print mktime(0, 0, 0, $earliest_date->month, $earliest_date->day, $earliest_date->year);
}

function get_schedule_end_date()
{
	global $latest_date;
	print mktime(0, 0, 0, $latest_date->month, $latest_date->day, $latest_date->year);
}

function get_time_increments()
{
	print TIME_INTERVAL;
}

function display_schedule($use_file, $requested_channel='all')
{
    // TODO: implement this feature: $requested_channel
	global $channels;
    if($use_file)
    {
        gather_schedule_info_from_file();
    }
    else
    {
        gather_schedule_info_from_db();
    }
	foreach($channels as $channel)
	{
		$channel->rearrange();
	}
	print_schedule();
}

function print_schedule()
{
	print '<div id="master-list">'; print "\n";
	print_header();
	print_content();
	print "</div>\n";
}

function print_content()
{
	global $channels;
	print '<div id="programme-list">'."\n";
	foreach($channels as $channel)
	{
		foreach($channel->programmes as $time => $p)
		{
			print '<div class="title '.$channel->id.'" start="'.$p->start.'" end="'.$p->end.'" code="'.$channel->id.'-'.$p->start.'" ';
			if($p->sub_title != null)
			{
				print 'sub-title='.$p->sub_title.' ';
			}
			if($p->is_new)
			{
				print 'new-programme=true';
			}
			print'>'.$p->title.'</div>'."\n";
		}
	}
	print "</div>\n";
}

function print_header()
{
	global $earliest_date, $latest_date;
	print '<div id="date-list">'."\n";
	$date_count = 0;
	for($date = clone $earliest_date; Date::compare($date, $latest_date) < 1; $date->addDay())
	{
		$time = mktime(0, 0, 0, $date->month, $date->day, $date->year);
		print '<div class="date" date="'.$date_count.'">';
		print date('D M d', $time);
		print "</div>\n";
		$date_count++;
	}
	print "</div>\n";
	
	print '<div id="time-list">'."\n";
	$time_count = 0;
	$curr_time = mktime(0, 0, 0, 0, 0, 0);
	$end_time = mktime(23, 60-TIME_INTERVAL, 0, 0, 0, 0);
	while($curr_time <= $end_time)
	{
		print '<div class="time" time="'.$time_count.'">';
		print date('H:i', $curr_time);
		print "</div>\n";
		$time_count++;
		$curr_time += TIME_INTERVAL * 60;
	}
	print "</div>\n";
}

function print_channels()
{
	global $channels;
	print '<ol id="channel-listing">'."\n";
	foreach($channels as $channel)
	{
		print '<li class="ui-widget-content channel"><img class="icon" src="'.$channel->icon.'" alt="icon"/><div class="channel-name">'.$channel->id.'</div></li>'."\n";
	}
	print "</ol>\n";
}

function gather_schedule_info_from_db()
{
    read_channels_from_db();
    read_programmes_from_db();
}

function gather_schedule_info_from_file()
{
	global $earliest_date, $latest_date;
	$file = "tvguide.xml";
	
	$xml = new XMLReader();
	if(!$xml->open($file))
	{
		// TODO-L: report that something is wrong
		exit;
	}
	
	while($xml->read())
	{
		if($xml->nodeType === XMLReader::ELEMENT)
		{
			if($xml->name === 'channel')
			{
				read_channels($xml);
			}
			else if($xml->name === 'programme')
			{
				read_programmes($xml);
			}
		}
		// otherwise continue
	}
}

function read_channels_from_db()
{
    global $channels;
    $list_of_channels = get_channels();
    foreach($list_of_channels as $channel)
    {
        $c = new Channel();
        $c->id = $channel['Channel'];
        $c->channel_number = $channel['ChannelNumber'];
        $c->icon = $channel['IconURL'];
        $channels[$c->id] = $c;
    }
}

function read_channels($xml)
{
	global $channels;
	
	$c = new Channel();
	
	$c->id = $xml->getAttribute('id');
	
	while($xml->read())
	{
		if($xml->nodeType === XMLReader::END_ELEMENT && $xml->name === 'channel')
		{
			$channels[$c->id] = $c;
			return;
		}
		else if($xml->nodeType === XMLReader::ELEMENT)
		{
			if($xml->name === 'display-name')
			{
				$xml->read();
				if($xml->nodeType === XMLReader::TEXT)
				{
					$c->channel_number = $xml->value;
				}
				// TODO-L: error handling
			}
			else if($xml->name === 'url')
			{
				$xml->read();
				if($xml->nodeType === XMLReader::TEXT)
				{
					$c->url = $xml->value;
				}
				// TODO-L: error handling
			}
			else if($xml->name === 'icon')
			{
				$xml->read();
				if($xml->nodeType === XMLReader::TEXT)
				{
					$c->icon = $xml->value;
				}
				// TODO-L: error handling
			}
		}
	}
	// TODO-L: something is wrong that we didn't encounter end tag, report the error
}

function read_programmes_from_db()
{
	global $earliest_date, $latest_date;
	global $channels;
    
    foreach($channels as $channel)
    {
        $timetable = get_timetable_for_channel($channel->id);
        foreach($timetable as $programming)
        {
            $p = new Programme();
            $p->start = $programming['start'];
            $p->is_new = $programming['is-new'];
            $date = new Date($p->start, false);
            
            // keep updating earliest_date and latest_date while reading the xml
            if(Date::compare($date, $earliest_date) == -1)
            {
                $earliest_date = $date;
            }
            if(Date::compare($date, $latest_date) == 1)
            {
                $latest_date = $date;
            }
            
            $p->title = $programming['title'];
            $p->sub_title = $programming['sub-title'];
			$channel->add_programme($p);
        }
    }
}

function read_programmes($xml)
{
	global $earliest_date, $latest_date;
	global $channels;
	
	$p = new Programme();
	
	$start_array = explode(' ', $xml->getAttribute('start'));
	$date = new Date(substr($start_array[0], 0, 8)); // assume local timezone, so ignore the timezone info
	$time = new Time(substr($start_array[0], 8));
	$p->start = mktime($time->hr, $time->min, 0, $date->month, $date->day, $date->year);
	
	// keep updating earliest_date and latest_date while reading the xml
	if(Date::compare($date, $earliest_date) == -1)
	{
		$earliest_date = $date;
	}
	if(Date::compare($date, $latest_date) == 1)
	{
		$latest_date = $date;
	}
	
	$channel_id = $xml->getAttribute('channel');
	$p->is_new = eval("return (".$xml->getAttribute('is-new').");" );
	
	while($xml->read())
	{
		if($xml->nodeType === XMLReader::END_ELEMENT && $xml->name === 'programme')
		{
			$channels[$channel_id]->add_programme($p);
			return;
		}
		else if($xml->nodeType === XMLReader::ELEMENT)
		{
			if($xml->name === 'title')
			{
				$xml->read();
				if($xml->nodeType === XMLReader::TEXT)
				{
					$p->title = $xml->value;
				}
				// TODO-L: error handling
			}
			else if($xml->name === 'sub-title')
			{
				$xml->read();
				if($xml->nodeType === XMLReader::TEXT)
				{
					$p->sub_title = $xml->value;
				}
				// TOOD-L: error handling
			}
		}
	}
	// TODO-L: something is wrong that we didn't encounter end tag, report the error
}

?>
