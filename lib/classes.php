<?php

define('END_DAY_TIME', '235500');
define('TIME_INTERVAL', 30);
require_once('lib/heap_sort.php');

class Channel
{
	public $id;
	public $channel_number;
	public $url;
	public $programmes = array();
	public $icon; // URL to the icon of this station
	
	function add_programme($p)
	{
		$this->programmes[(string)$p->start] = $p;
	}
	
	function rearrange()
	{
		$comparer = function($p1, $p2)
		{
			return Programme::compare($p1, $p2);
		};
		heap_sort($this->programmes, $comparer);
		$prev_programme = null;
		foreach($this->programmes as $p)
		{
			if($prev_programme != null)
			{
				$prev_programme->end = $p->start;
			}
			$prev_programme = $p;
		}
	}
}

class Programme
{
	public $start;
	public $end;
	public $title;
	public $sub_title = null;
	public $total_slots = 1; // number of 5 mins slots this programme occupies
	public $is_new;
	
	public static function compare($p1, $p2)
	{
		return $p1->start - $p2->start;
	}
}

class Date
{
	public $year;
	public $month;
	public $day;
	
	public function __construct($date, $type_string = true)
	{
        if($type_string)
        {
            $this->year = substr($date, 0, 4);
            $this->month = substr($date, 4, 2);
            $this->day = substr($date, 6);
        }
        else
        {
            $this->year = date('Y', $date);
            $this->month = date('m', $date);
            $this->day = date('d', $date);
        }
	}
	
	public function __toString()
	{
		return date('Ymd', mktime(0, 0, 0, $this->month, $this->day, $this->year));
	}
	
	public function addDay()
	{
		$newDate = mktime(0, 0, 0, $this->month, $this->day+1, $this->year);
		$this->year = date('Y', $newDate);
		$this->month = date('m', $newDate);
		$this->day = date('d', $newDate);
		
		return $this;
	}
	
	/*
	 * $d1 is later than $d2
	 */
	public static function countDays($d1, $d2)
	{
		$newDate = clone $d2;
		
		$total_days = 0;
		while($d1 != $newDate)
		{
			$total_days++;
			$newDate->addDay();
		}
		return $total_days;
	}
	
	public static function compare($d1, $d2)
	{
		if($d1->year > $d2->year)
		{
			return 1;
		}
		else if($d1->year < $d2->year)
		{
			return -1;
		}
		else
		{
			if($d1->month > $d2->month)
			{
				return 1;
			}
			else if($d1->month < $d2->month)
			{
				return -1;
			}
			else
			{
				if($d1->day > $d2->day)
				{
					return 1;
				}
				else if($d1->day < $d2->day)
				{
					return -1;
				}
				else
				{
					return 0;
				}
			}
		}
	}
}

class Time
{
	public $hr;
	public $min;
	public $sec;
	
	public function __construct($time, $type_string = true)
	{
        if($type_string)
        {
            $this->hr = substr($time, 0, 2);
            $this->min = substr($time, 2, 2);
            $this->sec = substr($time, 4);
        }
        else
        {
            $this->hr = date('H', $time);
            $this->min = date('i', $time);
            $this->sec = date('s', $time);
        }
	}
	
	public function __toString()
	{
		return date('His', mktime($this->hr, $this->min, $this->sec, 0, 0, 0));
	}
	
	public function addMin($mins)
	{
		$newDate = mktime($this->hr, $this->min + $mins, $this->sec, 0, 0, 0);
		$this->hr = date('H', $newDate);
		$this->min = date('i', $newDate);
		$this->sec = date('s', $newDate);
		return $this;
	}
	
	public static function compare($t1, $t2)
	{
		if($t1->hr > $t2->hr)
		{
			return 1;
		}
		else if($t1->hr < $t2->hr)
		{
			return -1;
		}
		else
		{
			if($t1->min > $t2->min)
			{
				return 1;
			}
			else if($t1->min < $t2->min)
			{
				return -1;
			}
			else
			{
				if($t1->sec > $t2->sec)
				{
					return 1;
				}
				else if($t1->sec < $t2->sec)
				{
					return -1;
				}
				else
				{
					return 0;
				}
			}
		}
	}
}

?>