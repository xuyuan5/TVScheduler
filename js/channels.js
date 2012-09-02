function collectChannelParameters() {
    // gather parameters
    var params = {};
    params['action'] = 'update';
    params['name'] = $('#channel-name').val();
    params['number'] = $('#channel-number').val();
    params['query'] = $('#query-url').val();
    params['icon'] = $('#icon-url').val();
    params['tag'] = $('#listing-tag').val();
    params['programming'] = $('#programming-class-name').val();
    params['showtime'] = $('#programming-time-class-name').val();
    params['showtitle'] = $('#programming-name-class-name').val();
    params['episode'] = $('#programming-ep-class-name').val();
    params['newepisode'] = $('#programming-new-ep-class-name').val();
    
    var queryName = new Array();
    var queryType = new Array();
    var defaultValue = new Array();
    
    $('#query-set .query').each(function() {
        queryName.push($(this).find('.name-input').val());
        queryType.push($(this).find('.select-box').val());
        defaultValue.push($(this).find('.value-input').val());
    });
    
    params['queries'] = queryName;
    params['querytypes'] = queryType;
    params['querydefaults'] = defaultValue;
    
    var regex = new Array();
    $('#regex-set .regex').each(function() {
        var newRegEx = $(this).find('.value-input').val();
        newRegEx = newRegEx.replace(/'/g, '\\\'');
        newRegEx = newRegEx.replace(/\\n/g, '\\\\n');
        newRegEx = newRegEx.replace(/\\d/g, '\\\\d');
        regex.push(newRegEx);
    });
    params['regex'] = regex;
    
    return params;
}

function configureChannel() {
    var params = collectChannelParameters();
    // send over via post
    $.post('schedules/channels.php', params, function(data) {
        presentChannels(data);
    });
    return false;
}

function deleteChannel(channel) {
    $.post('schedules/channels.php', {action: 'delete', name: channel}, function(data) {
        presentChannels(data);
    });
}

function getChannels() {
    $.get('schedules/channels.php', function(data) {
        presentChannels(data);
    });
}

function getChannelDetails(channel)
{
    $.get('schedules/channels.php', {channel: channel}, function(data) {
        updateChannelDetails(JSON.parse(data));
    });
}

function updateChannelDetails(channelDetails) {
    $('#channel-name').val(channelDetails.name);
    $('#channel-number').val(channelDetails.number);
    $('#query-url').val(channelDetails.query);
    $('#icon-url').val(channelDetails.icon);
    $('#listing-tag').val(channelDetails.tag);
    $('#programming-class-name').val(channelDetails.programming);
    $('#programming-time-class-name').val(channelDetails.showtime);
    $('#programming-name-class-name').val(channelDetails.showtitle);
    $('#programming-ep-class-name').val(channelDetails.episode);
    $('#programming-new-ep-class-name').val(channelDetails.newepisode);
    
    var count = channelDetails.queries.length;
    for(var i = 0; i < count; i++) {
        addNewQuery();
        $('#query-set .query').last().find('.name-input').val(channelDetails.queries[i].name);
        $('#query-set .query').last().find('.select-box').val(channelDetails.queries[i].type);
        $('#query-set .query').last().find('.value-input').val(channelDetails.queries[i].defaultValue);
    }
    
    count = channelDetails.regex.length;
    for(var i = 0; i < count; i++) {
        addNewRegex();
        $('#regex-set .regex').last().find('.value-input').val(channelDetails.regex[i]);
    }
}