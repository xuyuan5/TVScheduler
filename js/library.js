function addDays(theDate, days)
{
	theDate.setDate(theDate.getDate() + days);
}

function buildTime(interval, total_intervals)
{
	var total_min = interval * total_intervals;
	var hr = Math.floor(total_min/60);
	var min = total_min % 60;
	return formatTime(hr, min);
}

function formatTime(hr, min)
{
	return (hr < 10? "0"+hr:hr) + ":" + (min < 10? "0"+min:min);
}

Array.prototype.clear = function() 
{
	while(this.length > 0)
	{
		this.pop();
	}
}

Date.prototype.ToShortFormat = function()
{
	return formatTime(this.getHours(), this.getMinutes()) + ", " + this.toDateString();
}