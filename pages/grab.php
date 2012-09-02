<script type="text/javascript" src="js/grab.js"></script>
<script type="text/javascript" src="js/channels.js"></script>
<div class="page-title">Configure channels to retrieve broadcasting schedules.</div>
<button id="add-new">Add New Channel</button>
<button id='grab-schedules'>Grab All Schedules</button>
<div id='channels'>
</div>

<div id='temp-result'></div>

<div id='channel-edit'>
    <form id='channel-edit-form'>
        <div class='row'>
            Channel Name: <input class='name-input' id='channel-name' name="channel-name" required placeholder='CTV'/>
        </div>
        <div class='row'>
            Channel Number: <input class='name-input' type=number id='channel-number' name="channel-number" required />
        </div>
        <div class='row'>
            Query URL: <input type=url class='url-input' id='query-url' name="query-url" required />
        </div>
        <div class='row'>
            Icon URL: <input type=url class='url-input' id='icon-url' name="icon-url" />
        </div>
        <div class='row'>
            <fieldset>
                <legend> Query Parsing Rule </legend>
                <div class='row'>
                    Listing Tag: <input type=text class='name-input' id='listing-tag' name="listing-tag" required />
                </div>
                <div class='row'>
                    Programming Class Name: <input type=text class='name-input' id='programming-class-name' name="programming-class-name" required />
                </div>
                <div class='row'>
                    Programming Time Class Name: <input type=text class='name-input' id='programming-time-class-name' name="programming-time-class-name" required />
                </div>
                <div class='row'>
                    Programming Name Class Name: <input type=text class='name-input' id='programming-name-class-name' name="programming-name-class-name" required />
                </div>
                <div class='row'>
                    Programming Episode Class Name: <input type=text class='name-input' id='programming-ep-class-name' name="programming-ep-class-name" required />
                </div>
                <div class='row'>
                    Programming New Episode Class Name: <input type=text class='name-input' id='programming-new-ep-class-name' name="programming-new-ep-class-name" required />
                </div>
            </fieldset>
        </div>
        <div class='row'>
            <fieldset id='query-set'>
                <legend> Query Parameters </legend>
                <div class='row'>
                    <button class='add-button' id="add-query">Add New Query</button>
                </div>
            </fieldset>
        </div>
        <div class='row'>
            <fieldset id='regex-set'>
                <legend> Ignore Regex </legend>
                <div class='row'>
                    <button class='add-button' id="add-regex">Add New Regex</button>
                </div>
            </fieldset>
        </div>
        <div class='row'>
            <button id='submit-channel'>Submit</button>
        </div>
    </form>
</div>

<div id='grab-template' class='template'>
    <div class='query row'>
        <fieldset>
            <legend>Query</legend>
            <div class='row'>
                Query Name: <input type=text class='name-input'> 
            </div>
            <div class='row'>
                Query Type: 
                <select class='select-box'>
                    <option value='date-ms'>Date</option>
                    <option value='string'>String</option>
                    <option value='int'>Number</option>
                </select> 
            </div>
            <div class='row'>
                Default Value: <input type=text class='value-input'/>
            </div>
        </fieldset>
        <button class='delete'>X</button>
    </div>
    <div class='regex row'>
        Regex: <input type=text class='value-input' />
        <button class='delete'>X</button>
    </div>
    <div class='channel'>
    </div>
</div>