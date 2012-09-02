$(document).ready(function() {
    buildUI();
    getChannels();
});

function buildUI() {
    $( 'button' ).button();
    $( 'button#add-new' ).click(function(){addNewChannel(true);});
    $( 'button#add-new' ).position({
        of: $('div.page-title'),
        my: "left top",
        at: "left bottom",
        offset: "0 20"
    });
    
    $( 'button#grab-schedules' ).click(function(){grabAllSchedules('all');});
    $( 'button#grab-schedules' ).position({
        of: $('button#add-new'),
        my: "left top",
        at: "right top",
        offset: "5 0"
    });
    
    $( 'button#add-query' ).click(addNewQuery);
    $( 'button#add-regex' ).click(addNewRegex);
    $( 'button#submit-channel' ).click(configureChannel);
    
    $( '#channels' ).position({
        of: $('button#add-new'),
        my: "left top",
        at: "left bottom",
        offset: "0 20"
    });
}

function editChannel() {
    addNewChannel(false);
    getChannelDetails($(this).siblings('.channel-name').text());
}

function removeChannel() {
    deleteChannel($(this).siblings('.channel-name').text());
}

function deleteParent() {
    $(this).parent().remove();
    return false;
}

function addNewChannel(isNew) {
    resetChannelEditForm(isNew);
    $('#channel-edit').dialog({
        modal: true,
        title: isNew?'Create New':'Edit',
        resizable: false,
        width: 500,
        height: 650
    });
}

function resetChannelEditForm(isNew) {
    $(':input', '#channel-edit form').val('');
    $('#channel-edit #query-set .query').remove();
    $('#channel-edit #regex-set .regex').remove();
    if(isNew){
        $('#channel-edit #channel-name').attr({disabled:false});
        $('#channel-edit #channel-number').attr({disabled:false});
    } else {
        $('#channel-edit #channel-name').attr({disabled:true});
        $('#channel-edit #channel-number').attr({disabled:true});
    }
}

function addNewQuery() {
    var cloned = $('#grab-template .query').clone();
    $('#query-set').append(cloned);
    cloned.find('.delete').click(deleteParent);
    return false;
}

function addNewRegex() {
    var cloned = $('#grab-template .regex').clone();
    $('#regex-set').append(cloned);
    cloned.find('.delete').click(deleteParent);
    return false;
}

function presentChannels(channelsData) {
    $('#channels').empty();
    $('#channels').append(channelsData);
    $('#channels button').button();

    $('#channels button.channel-edit').click(editChannel);
    $('#channels button.channel-delete').click(removeChannel);
    $('#channels button.channel-grab').click(grabSchedules);
    
    $('#channel-edit').dialog('close');
}

function grabAllSchedules(target) {
    // TODO: add start-date options
    $.post('schedules/grabber.php', {target: target}, function(data) {
        // TODO: remove the following, when a better debugging method can be discovered
        $( '#temp-result' ).position({
            of: $('#channels'),
            my: "left top",
            at: "left bottom",
            offset: "0 20"
        });
        $('#temp-result').text(data);
        if(data == null || data.length == 0) {
            window.location = "?action=display&channel="+target;
        }
    });
}

function grabSchedules()
{
    grabAllSchedules($(this).siblings('.channel-name').text());
}