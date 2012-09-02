<script type="text/javascript" src="js/recordings.js"></script>
<script type="text/javascript" src="js/display.js"></script>
<?php
require_once('lib/classes.php');
require_once('database/channels.php');
require_once('schedules/schedule.php');
require_once('lib/web.php');

if(isset($_GET['channel']))
{
    display_schedule(FALSE, $_GET['channel']);
}
else
{
    display_schedule(FALSE);
}
?>

<!-- container for the UI -->
<div id="ui">
    <div id="toolbar">
        <ul>
            <li><a href="#list-schedule">List Schedules</a></li>
            <li><a href="#find-shows">Find Show</a></li>
            <li><a href="schedules/recordings.php" title="active recordings">Active Recordings</a></li>
        </ul>
        <div id="list-schedule">
            <table id="action-table">
                <tr>
                <td width="30%">
                    Choose stations:
                </td>
                <td width="auto">
                    And then choose desired date/time:
                </td>
                </tr>
                <tr>
                <td width="30%">
                    <div id="channel-selector">
                        <?php
                        print_channels();
                        ?>
                    </div>
                </td>
                <td width="auto">
                    <div id="date-selector">
                        Date: <input class="slider-input" disabled="true" type="text" id="date-from" /> - <input class="slider-input" disabled="true" type="text" id="date-to" />
                        <div class="slider" id="date-slider" start-date="<?php get_schedule_start_date(); ?>" end-date="<?php get_schedule_end_date(); ?>"></div>
                    </div>
                    <div id="time-selector">
                        Time: <input class="slider-input" disabled="true" type="text" id="time-from" /> - <input class="slider-input" disabled="true" type="text" id="time-to" />
                        <div class="slider" id="time-slider" step="<?php get_time_increments(); ?>"></div>
                    </div>
                </td>
                </tr>
            </table>
            <div id="schedule-result">
            </div>
        </div>
        <div id="find-shows">
            <div id="shows-filter">
                <label for="show">Show Name: </label>
                <input id="show" />
                <input type="checkbox" id="new-show-only" /> New Show Only
            </div>
            <div id="shows-result">
            </div>
        </div>
        <div id="active-recordings">
        </div>
    </div>
</div>
<div id="details" title="">
    <p>
    <div id="sub-title"></div>
    <div id="new-show">NEW</div></br>
    <span>Start:</span> <div id="start-time"></div></br>
    <span>End:</span> <div id="end-time"></div>
    </p>
    <a id="queue" href="#" channel="" start="" end="">Record</a><a id="dequeue" href="#" channel="" start="" end="">Clear Recording</a>
</div>
