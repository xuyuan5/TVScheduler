function getRecordingsSucceed(data)
{
    dataArray = JSON.parse(data);
    // TODO: beautify it, maybe get the div created from the php instead of JSON
    
    result = "";
    for(i = 0; i<dataArray.length; i++)
    {
        result += createActiveRecordingDiv(dataArray[i]);
    }
    $('div#active_recordings').html(result);
}

function manipulateQueue(action) {
    var channel = $('#'+action).attr('channel');
    var start = $('#'+action).attr('start');
    var end = $('#'+action).attr('end');
    $.get('schedules/queue.php', {action: action, channel: channel, start: start, end: end}, function(data, textStatus) {
    alert(data);
        $('#details').dialog('close');
    });
}

function queueActionClicked()
{
    if(!$(this).button('option', 'disabled'))
    {
        manipulateQueue('queue');
    }
}

function updateQueueButtonState() {
    var channel = $('#details a#queue').attr('channel');
    var start = $('#details a#queue').attr('start');
    var end = $('#details a#queue').attr('end');
    $.get('schedules/queue.php', {action: 'update', channel: channel, start: start, end: end}, function(data, textStatus) {
        if($.trim(data) == "true") {
            $('#details a#dequeue').button('enable');
            $('#details a#queue').button('disable');
        }
        else {
            $('#details a#dequeue').button('disable');
            $('#details a#queue').button('enable');
        }
    });
}

function dequeueActionClicked()
{
    if(!$(this).button('option', 'disabled'))
    {
        manipulateQueue('dequeue');
    }
}

function createActiveRecordingDiv(recording)
{
    result = '<div class="recording">'+
             recording.Channel + ': ' + recording.StartTime +
             ' - ' + recording.EndTime+ '</div>'
    return result;
}