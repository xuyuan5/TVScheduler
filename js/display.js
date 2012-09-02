$(document).ready(function() {
    buildUI();
    $( "#date-slider" ).slider("disable");
    $( "#time-slider" ).slider("disable");
    $( '#master-list' ).hide();
});

function buildUI()
{
    $( "#toolbar" ).tabs({
        ajaxOptions: {
            success: getRecordingsSucceed
        }
    });
    $( "#channel-listing" ).selectable( {
        stop: channelSelected
    });
    buildSliders();
    $( '#show' ).keyup(autoCompleteShowSearch);
    
    $( '.title' ).click(
        function() {
            showDetails($(this));
        }
    );
    
    $( 'div#details a' ).button();
    $( 'div#details a#queue' ).click(queueActionClicked);
    $( 'div#details a#dequeue' ).click(dequeueActionClicked);

    $('#new-show-only').click(autoCompleteShowSearch);
}

function autoCompleteShowSearch()
{
    var newOnly = $('input:checked').length == 1;
    $('#shows-result').empty();
    var searchString = $('#show').val();
    if(searchString.length <= 0)
    {
        $('#shows-result').height(0);
        return;
    }
    var matchregex = new RegExp('^' + searchString, 'i');
    var index = 0;
    var items_per_row = Math.floor($('#shows-result').width() / 100);
    $( '#master-list .title' ).each( function() {
        if($(this).text() == "no data" || $(this).text().match(matchregex)==null)
        {
            return;
        }
        if(newOnly)
        {
            if($(this).attr('new-programme')==null)
            {
                return;
            }
        }
        var programme = $(this).clone(true);
        $('#shows-result').append(programme);

        programme.width(100);
        programme.height(60);
        programme.position({
            of: $('#shows-filter'),
            my: "left top",
            at: "left bottom",
            offset: (100*(index%items_per_row)) + " " + (30+60*Math.floor(index/items_per_row)),
            collision: "none none"
        });
        index++;
        programme.addClass('ui-state-default');
        programme.hover(
            function()
            {
                $(this).addClass('ui-state-hover');
            },
            function()
            {
                $(this).removeClass('ui-state-hover');
            }
        );
        
        $( '#shows-result .title' ).click(
            function() {
                showDetails($(this));
            }
        );
    });
    $('#shows-result').height(30+60*(1+Math.floor((index-1)/items_per_row)));
}

function showDetails(ui)
{
    var startTime = ui.attr('start');
    var endTime = ui.attr('end');
    var subTitle = ui.attr('sub-title');
    var newShow = ui.attr('new-programme');
    var channel = ui.attr('code').split('-')[0];
    
    if(subTitle != null)
    {
        $('#details #sub-title').text(subTitle);
        $('#details #sub-title').show();
    }
    else
    {
        $('#details #sub-title').hide();
    }
    $('#details #start-time').text((new Date(startTime*1000)).ToShortFormat());
    $('#details #end-time').text((new Date(endTime*1000)).ToShortFormat());
    if(newShow != null)
    {
        $('#details #new-show').show();
    }
    else
    {
        $('#details #new-show').hide();
    }
    
    $('#details #queue').attr('channel', channel);
    $('#details #dequeue').attr('channel', channel);
    $('#details #queue').attr('start', startTime);
    $('#details #dequeue').attr('start', startTime);
    $('#details #queue').attr('end', endTime);
    $('#details #dequeue').attr('end', endTime);
    
    $('#details').dialog({
        modal: true,
        title: ui.text()
    });
    
    updateQueueButtonState();
}

var selected_channels = new Array();
var date_nums = [0, 0];
var time_nums = [0, 0];

function channelSelected()
{
    selected_channels.clear();
    $( ".ui-selected .channel-name", this ).each(function() {
        selected_channels.push($(this).text());
    });
    if(selected_channels.length > 0)
    {
        $( "#date-slider" ).slider("enable");
        $( "#time-slider" ).slider("enable");
    }
    else
    {
        $( "#date-slider" ).slider("disable");
        $( "#time-slider" ).slider("disable");
    }
    buildSchedules();
}

function buildSchedules()
{
    var num_channels = selected_channels.length;
    var date, time, channel_index;
    var temp_div, container, reference_table;
    
    if(num_channels > 0)
    {
        reference_div = $('#action-table');
        container = $('#schedule-result');
        container.empty();

        // display dates
        var index = 0;
        for(date = date_nums[0]; date <= date_nums[1]; date++)
        {
            temp_div = $('.date[date='+date+']').clone(true);
            temp_div.width(100*num_channels);
            container.append(temp_div);
            temp_div.position({
                of: reference_div,
                my: "left top",
                at: "left bottom",
                offset: (50+100*num_channels*(index)) + " 0",
                collision: "none none"
            });
            index++;
        }
        $('#toolbar').width(Math.max(50+100*num_channels*index, $(document).width()));

        // display time
        index = 0;
        for(time = time_nums[0]; time <= time_nums[1]; time++)
        {
            temp_div = $('.time[time='+time+']').clone(true);
            temp_div.height(60);
            container.append(temp_div);
            temp_div.position({
                of: reference_div,
                my: "left top",
                at: "left bottom",
                offset: "0 " + (30+60*index),
                collision: "none none"
            });
            index++;
        }
        container.height(30+60*index);

        // construct two date+time number for start/end time
        var time_zone_offset = (24 - (new Date()).getTimezoneOffset() / 60) * 3600;
        for(channel_index = 0; channel_index < num_channels; channel_index++)
        {
            var start_time = parseInt($("#date-slider").attr("start-date"));
            var end_time = parseInt($("#date-slider").attr("end-date"));
            $('#master-list #programme-list .title.' + selected_channels[channel_index]).each(function() {
                var start = $(this).attr('start') - start_time;
                var date_index = Math.floor(start/3600/24);
                var time_index = ((start%(3600*24))/60)/30;
                var end = $(this).attr('end') - start_time;
                var end_time_index = ((end%(3600*24))/60)/30;

                if(time_index > time_nums[1])
                {
                    return;
                }
                if(end_time_index <= time_nums[0])
                {
                    return;
                }
                if(time_index < time_nums[0])
                {
                    time_index = time_nums[0];
                }
                if(end_time_index > time_nums[1])
                {
                    end_time_index = time_nums[1];
                }
                if(date_index < date_nums[0])
                {
                    return;
                }
                if(date_index > date_nums[1])
                {
                    return;
                }

                temp_div = $(this).clone(true);
                temp_div.width(100);
                temp_div.height(60*(end_time_index-time_index));
                container.append(temp_div);
                temp_div.position({
                    of: reference_div,
                    my: "left top",
                    at: "left bottom",
                    offset: (50+100*(num_channels*(date_index-date_nums[0])+channel_index)) + " " + (30+60*(time_index-time_nums[0])),
                    collision: "none none"
                });
                temp_div.addClass('ui-state-default');
                temp_div.hover(
                    function()
                    {
                        $(this).addClass('ui-state-hover');
                    },
                    function()
                    {
                        $(this).removeClass('ui-state-hover');
                    }
                );
            });
        }
    }
}

function buildSliders()
{
    var startDate = new Date(parseInt($("#date-slider").attr("start-date"))*1000);
    var endDate = new Date(parseInt($("#date-slider").attr("end-date"))*1000);
    var currDate = new Date(parseInt($("#date-slider").attr("start-date"))*1000);
    
    var numDays = 0;
    while(currDate < endDate)
    {
        addDays(currDate, 1);
        numDays++;
    }
    
    $( "#date-slider" ).slider({
        range: true,
        min: 0,
        max: numDays,
        values: [ 0, numDays ],
        slide: dateSliderSlide
    });
    $( "#date-from" ).val(startDate.toDateString());
    $( "#date-to" ).val(endDate.toDateString());
    
    var interval = $("#time-slider").attr("step");
    var maxTime = 24*60/interval - 1;
    $( "#time-slider" ).slider({
        range: true,
        min: 0,
        max: maxTime,
        values: [ 0, maxTime ],
        slide: timeSliderSlide
    });
    $( "#time-from" ).val( buildTime(interval, 0) );
    $( "#time-to" ).val( buildTime(interval, maxTime) );
    
    date_nums[1] = numDays;
    time_nums[1] = maxTime;
}

function dateSliderSlide(event, ui)
{
    var startDate = new Date(parseInt($("#date-slider").attr("start-date"))*1000);
    var endDate = new Date(parseInt($("#date-slider").attr("start-date"))*1000);
    addDays(startDate, ui.values[ 0 ]);
    addDays(endDate, ui.values[ 1 ]);
    $( "#date-from" ).val( startDate.toDateString() );
    $( "#date-to" ).val( endDate.toDateString() );
    
    date_nums[0] = ui.values[ 0 ];
    date_nums[1] = ui.values[ 1 ];
    
    buildSchedules();
}

function timeSliderSlide(event, ui)
{
    var interval = $("#time-slider").attr("step");
    $( "#time-from" ).val( buildTime(interval, ui.values[ 0 ]) );
    $( "#time-to" ).val( buildTime(interval, ui.values[ 1 ]) );
    
    time_nums[0] = ui.values[ 0 ];
    time_nums[1] = ui.values[ 1 ];
    
    buildSchedules();
}
