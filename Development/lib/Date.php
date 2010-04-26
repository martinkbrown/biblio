<?php

define('DATE','M j, Y');
define('FULL_DATE','M j, Y h:m a');
define('SHORT_DATE','m/d/Y');
define('TIME','h:i a');

class Date
{
	function getDate($timestamp,$format=DATE)
	{
		if($timestamp == 0)	return;
		return date($format,$timestamp);
	}

        function getYear($timestamp)
        {
            return date('Y',$timestamp);
        }
	
	function getDateDifference($t1,$format = DATE)
	{
		$t2 = time();
		
		$day = 60 * 60 * 24;
		
		if(date('j',$t1) == date('j',$t2) && date('n',$t1) == date('n',$t2) && date('Y',$t1) == date('Y',$t2))
		{
			return "Today";
		}
		else 
		{
			$seconds = time() - $t1;
			$days = (int) ($seconds / $day);
			
			if($days <= 7)
			{
				switch($days)
				{
					case 0:
						return "A few hours ago";
					break;
					case 1:
						return "Yesterday";
					break;
					default:
						return $days." days ago";
					break;
				}
			}
			else if($days <= 31)
			{
				$weeks = (int)	($days / 7);
				
				switch($weeks)
				{
					case 1:
						return "One week ago";
					
					default:
						return $weeks." weeks ago";
					break;
				}
			}
			else 
			{
				$months = (int) ($weeks / 30);
				
				switch($months)
				{
					case 0:
						return "One month ago";
					
					default:
						return $months." weeks ago";
					break;
				}
			}
		}
	}
	
	function getTime($timestamp,$format=TIME)
	{
		if($timestamp == 0)	return;
		return date($format,$timestamp);
	}
	function getFormDate($fieldName)
	{
		$month = $fieldName . '_month';
		$day = $fieldName . '_day';
		$year = $fieldName .'_year';
		
		return mktime(0,0,0,param($month),param($day),param($year));
	}
	
	function getFormTime($name)
	{
		$hour = param($name."_hour");
		$min = param($name."_min");
		$mer = param($name."_format");
		if($hour==12 && $mer == "am") $hour = 0;
                else if($hour == 12 && $mer == "pm") $hour = 12;
                else if($mer == "pm") $hour = $hour + 12;
                
		return mktime($hour,$min,0,0,0,0);
	}

        function getFormTimeFromHourVariableName($hour)
        {
           $pos = strpos($hour,"_hour");
            if($pos)
            {
                $time = substr($hour,0,$pos);
                $time = date::getFormTime($time);
            }

            return $time;
        }

        function getFormDateFromDayVariableName($day)
        {
            $pos = strpos($day,"_day");
            if($pos)
            {
                $date = substr($day,0,$pos);
                $date = date::getFormDate($date);
            }

            return $date;
        }

        function getDateRange($timestamp1,$timestamp2)
        {
            if(date('Y',$timestamp1) != date('Y',$timestamp2))
            {
                return date('M j, Y',$timestamp1) . " - " . date('M j, Y',$timestamp2);
            }
            
            $date = date('M j',$timestamp1);

            if(date('M',$timestamp1) != date('M',$timestamp2))
            {
                return $date . " - " . date('M j, Y',$timestamp2);
            }

            return $date . " - " . date('j, Y',$timestamp2);
            
        }
}

?>