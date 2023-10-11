//////////////////////////////////////////////////////////
// THIS SCRIPT MUST BE INCLUDED IN index.blade.php
//          OF QR CODE SCANNER PAGE.
//-------------------------------------------------------
// CREATED: SEPTEMBER 29, 2023
//-------------------------------------------------------
//
// ------------------------------------------------------
/* #region BASE CODE */
// ------------------------------------------------------
//
var studentIdSet = undefined;
var dtRowClonesMap = undefined;
var lastAddedRow = null;
var dataTable = undefined;

var cameraSelectMenu = null;
var qrcScanner = null;

const cameraSelectClass = ".camera-selector";
const qrcSurfaceId = '#camera-view';

const openCameraBtn = ".btn-open-cam";
const stopCameraBtn = ".btn-stop-cam";
const refreshCamListButton = ".btn-refresh-cam-list";
const attendanceScrollView = '.attendance-scrollview';
const attendanceScrollViewRoot = '.attendance-scrollview-root';
const attendanceTable = ".attendance-table";
 
const UPDATE_TICK = 1000; // 1second -> 1000millisec
const ON_START_STOP_STREAM_DELAY = 3000;

const DEFAULT_SCANNER_TIPS = `Please select a camera below. Click on \"<i class="fas fa-rotate px-1"></i>\" to refresh if it\'s not detected.`;
const AJAX_GENERAL_ERROR = "A fatal error has occurred. If this message continues to appear, please contact the system administrator.";
const SCANNER_TIP_CLICK_OPEN = "Click on \"Open\" to start the scanner.";
const SCANNER_TIP_PRESENT_QR = "Present your QR Code towards the scanner.";
const WARN_INTERACT_TABLE = "Please stop the camera to interact with this table.";

var FLAG_IS_CAM_OPEN = false;
var FLAG_PAUSE_SCAN = false;

$(document).ready(function ()
{
    __onAwake();

    __bindEvents();

    __fixedUpdate();
});

// Initialization goes here
function __onAwake()
{
    // We will use this to count all students from the attendance sheet
    // using their Ids but we need to only count similar Ids as one (distinct)
    studentIdSet = new Set();

    // All newly added rows must set an entry (clone) here 
    // so that they can be identified later during Check/TimeOut
    dtRowClonesMap = new Map();
  
    // Initialize the camera selectmenu and disable it on load
    cameraSelectMenu = new ComboBox(cameraSelectClass, true);

    // Create an instance of QR Scanner
    qrcScanner = new QRCodeScanner(qrcSurfaceId);

    // Get the attached cameras on load
    getAttachedCameras();
 
    // Use this to set maximum displayed pagination numbers
    jQuery.fn.dataTableExt.pager.numbers_length = 5;

    dataTable = new DataTable(attendanceTable, 
    {
        "pagingType"    : "full_numbers",    // Show the First, Previousm Next and Last pagination buttons
        "lengthChange"  : false,
        "searching"     : false,
        "autoWidth"     : false,
        "order"         : [[4, 'desc']],    // The 5th <th> is the row-timestamp, required for sorting on prepend
    }); 
    
    countDistinctStudents();
    cloneTimedInRows();
}

// Handle events here
function __bindEvents() 
{
    $(refreshCamListButton).on('click', () => 
    {    
        showScannerTips(DEFAULT_SCANNER_TIPS);
        getAttachedCameras();
    });

    $(openCameraBtn).on('click', () => openSelectedCamera());
    $(stopCameraBtn).on('click', () => stopScanner());

    qrcScanner.OnCameraStartFailed((err) => showErrorDialog(err));
    qrcScanner.OnCameraStopFailed( (err) => showErrorDialog(err));

    qrcScanner.OnCameraStarting( () => 
    {
        showOpenCamButton(false);                                   // Hide the "Open" camera button
        showCameraPermsWarn(false);                                 // Hide the camera permissions warning
        showCameraSelectMenu(false);                                // Disable the camera selectmenu
        showCameraPrepLoader(true, "Preparing the scanner...");     // Show the loading spinner
    });

    qrcScanner.OnCameraStarted( () => 
    {
        FLAG_IS_CAM_OPEN = true;

        showCameraPrepLoader(false);                                // Hide the loading spinner
        $(attendanceScrollView).toggleClass('pe-none', true);       // Lock the attendance sheet from interactions then
        showCrosshairs(true);                                       // Show crosshairs
        showScannerTips(SCANNER_TIP_PRESENT_QR);                    // Show some tip / guides

        setTimeout(() => {
            
            showStopCamButton(true);

        }, 3000); 
    });

    qrcScanner.OnCameraStopping(() => 
    {
        showStopCamButton(false);                               // Hide the stop button
        showCrosshairs(false);                                  // Hide crosshairs 
        showCameraPrepLoader(true, "Stopping the scanner...");  // Show the loading spinner
    });

    qrcScanner.OnCameraStopped( () => 
    {
        setTimeout(() => 
        {
            FLAG_IS_CAM_OPEN = false;

            // Allow the attendance sheet from recieving interactions
            $(attendanceScrollView).toggleClass('pe-none', false);

            showOpenCamButton(true);
            showCameraSelectMenu(true);
            showCameraPrepLoader(false);        // Hide the loading spinner
            showCameraPermsWarn(true);          // then show camera permissions warn
            showScannerTips(SCANNER_TIP_CLICK_OPEN);

        }, 3000);
    });

    qrcScanner.OnScanResult( (content) => 
    {
        if (FLAG_PAUSE_SCAN)
            return;

        var metaCSRF = $('meta[name="csrf-token"]').attr('content');

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': metaCSRF },
            error: (xhr) => showErrorDialog(AJAX_GENERAL_ERROR)
        });

        // (url, data, success) <-- auto pass success 'response' param
        $.post(SCAN_POST_URL, { studentNo: content }, processAttendanceViewData);
    });

    // Check user clicks onto the attendance table
    $(attendanceScrollViewRoot).click(() => {

        if (FLAG_IS_CAM_OPEN !== false)
            showWarnDialog(WARN_INTERACT_TABLE);
    });
}
//
// Execute every 1second
//
function __fixedUpdate()
{
    // Show the current time
    updateTimeLabel();

    // Show current date
    updateCalendarLabel();

    // Loop this function infinitely
    setTimeout(__fixedUpdate, UPDATE_TICK);
}
// ------------------------------------------------------
/* #endregion BASE CODE */
// ------------------------------------------------------

// ------------------------------------------------------
/* #region BUSINESS LOGIC */
// ------------------------------------------------------

function updateTimeLabel()
{ 
    $(".hour-minute-label") .text( moment().format("h:mm") );   // hour:minute
    $(".seconds-label")     .text( moment().format(":ss") );    // seconds with leading 0
    $(".day-night-label")   .text( moment().format("A") );      // AM / PM
}
//
// Get the name of day from Monday ~ Sunday including the 
// current month in abbreviated form, current day and year
// then show it onto the calendar label
// 
function updateCalendarLabel()
{
    var currentDate = moment().format('dddd, MMM. DD, YYYY');

    $(".date-label").text(currentDate);
}

//.......................................................
// BEGIN CAMERA SELECTION
//.......................................................
//
// Detect all attached camera devices then list them on a select menu
// and store each of their object instance in memory
//
function getAttachedCameras()
{
    enableCamRefreshButton(false);
    showOpenCamButton(false);

    // Disable the select menu then clear its items
    cameraSelectMenu.ClearItems();
    cameraSelectMenu.Disable();

    // Set the default option to "Select Camera"
    cameraSelectMenu.AddItem('', 'Select Camera', true, true);

    qrcScanner.GetCamerasAsync( 
        // On Success
        (cameraDetails) =>
        {   
            // List all cameras onto the selectmenu dropdown list
            $.each(cameraDetails, (index, camera) => {
                cameraSelectMenu.AddItem(camera.deviceId, camera.deviceName);
            });

            // Rebuild the camera selectmenu then add an event handler
            // when a camera was selected from the menu
            cameraSelectMenu.OnSelect(OnCameraItemSelected);

            cameraSelectMenu.Enable();
            cameraSelectMenu.Refresh();
            enableCamRefreshButton();
        },

        // On Failed
        (err) =>
        {
            showWarnDialog(err);
        }); 
}
//
//
//
function showCameraSelectMenu(show)
{
    if (!show)
    {
        cameraSelectMenu.Disable();
        enableCamRefreshButton(false);
        return;
    }

    cameraSelectMenu.Enable();
    enableCamRefreshButton();
}
//
//
//
function OnCameraItemSelected(event, ui)
{
    // Only show the "Open" button when there is a valid camera selected
    if (cameraSelectMenu.GetSelectedItem())
    {
        showScannerTips(SCANNER_TIP_CLICK_OPEN);
        showOpenCamButton(true);
        return;
    }

    showScannerTips(DEFAULT_SCANNER_TIPS);
    showOpenCamButton(false);
} 
//.......................................................
// END: CAMERA SELECTION
//.......................................................
//
//
//.......................................................
// BEGIN CAMERA CONTROL BUTTONS
//.......................................................
//
function enableCamRefreshButton(enable = true)
{
    $(refreshCamListButton).toggleClass('disabled', !enable);
}
//
//
//
function showOpenCamButton(show)
{
    if (!show)
    {
        $(openCameraBtn).hide();
        return;
    }

    $(openCameraBtn).toggleClass('d-none', false).show();
}
//
//
//
function showScannerTips(tips)
{
    if (tips === undefined || tips === '')
        return;

    $(".scanner-tips").html(`<i class="fas fa-info-circle me-1"></i>${tips}`);
}
//
//
//
function showStopCamButton(show)
{
    if (!show)
    {
        $(stopCameraBtn).toggleClass('d-none', true);
        return;
    }

    $(stopCameraBtn).toggleClass('d-none', false);
}
//
//.......................................................
// END CAMERA CONTROL BUTTONS
//.......................................................
//
//
//.......................................................
// BEGIN CAMERA STREAMING
//.......................................................
//
//
// Begin rendering the camera according to the camera id that was
// selected from the selectmenu
//
function openSelectedCamera()
{
    qrcScanner.Open(cameraSelectMenu.GetSelectedItem());
} 
//
// Stop the scanner then close the camera device
//
function stopScanner()
{
    qrcScanner.Close();
}
//
//
//
function showCrosshairs(show)
{
    $(".webcam-overlay-crosshair").toggleClass('d-none', !show);
}
//
//
//
function showCameraPermsWarn(show)
{
    $(".webcam-overlay-perms-warn").toggleClass('d-none', !show);
}
//
//
//
function showCameraPrepLoader(show, prepText)
{
    $('.webcam-overlay-spinner .prep-text').text( (prepText !== undefined || prepText !== '') ? prepText : '');
    $('.webcam-overlay-spinner').toggleClass('d-none', !show);
}
//
//.......................................................
// END CAMERA STREAMING
//.......................................................
//
// ------------------------------------------------------
/* #endregion BUSINESS LOGIC  */
// ------------------------------------------------------
//
//
// ------------------------------------------------------
/* #region ASYNCHRONOUS OPERATIONS  */
// ------------------------------------------------------

function processAttendanceViewData(viewData)
{ 
    if (viewData)
    {
        var response = JSON.parse(viewData);
        var responseSuccessfulTimeIn = '0x5';
        var responseSuccessfulTimeOut = '0x6';
        const responseUnrecognizedSN = '0x4';

        switch (response.status)
        {
            case responseSuccessfulTimeIn:

                if (response.data)
                    onSuccessfulTimein(response.data);

                break;

            case responseSuccessfulTimeOut:

                if (response.data)
                    onSuccessfulTimeout(response.data);

                break;
            
            case responseUnrecognizedSN:
                
                FLAG_PAUSE_SCAN = true;
                showWarnDialog(response.message, 'Warning', 'OK', undefined, () => {
                    FLAG_PAUSE_SCAN = false;
                });
                //showWarnDialog(message, title, okButton, cancelButton, onOK, onCancel) 
                break;
        }
    }
}
//
//
//
function onSuccessfulTimein(data)
{
    var timeIn = data.time_in || '';
 
    if (!timeIn || timeIn == '' || timeIn === undefined)
    {
        showErrorDialog("Failed to retrieve Time In record. Please try again.");
        return;
    }

    timeIn = moment(timeIn).format('h:mm A');      // Format the TimeIn string

    var tr = $('<tr>')
        .addClass(`row-index-${data.student_no}`)
        .attr('data-student-no', data.student_no)
        .attr('data-order', 0)
        .append(`<td class="td-name-details">
        <div class="d-flex align-items-center w-100">
            <img src="${data.photo}" alt=""
                style="width: 45px; height: 45px" class="rounded-circle" />
            <div class="ms-3 w-100 text-truncate">
                <p class="fw-bold mb-1 text-truncate">${data.name}</p>
                <p class="text-muted mb-0 text-truncate">${data.student_no}</p>
            </div>
        </div>
        </td>
        <td class="td-20">
            <span class="attendance-time-label time-in-label time-in">${timeIn}</span>
        </td>
        <td class="td-20">
            <span class="attendance-time-label time-out-label"></span>
            <input type="hidden" class="timeout-val">
        </td>
        <td class="td-20"> 
            <span class="text-primary-color font-condensed-bold attendance-time-label duration duration-label"></span>
        </td>
        <td class="d-none row-timestamp">${moment().format("YYYY-MM-DD HH:mm:ss")}</td>`);
 
    if (lastAddedRow) 
    {
        console.log('removing from last row');
        $(lastAddedRow).removeClass('highlight-row-timein').removeClass('highlight-row-timeout');
    }

    tr.addClass('highlight-row-timein');

    // Chaining -> add the new row, render it then get a <tr> reference to it
    var rowNode = dataTable.row.add(tr).draw().node();
 
    // Make an entry of the new row node into the map with its key as student No
    // and value as the new row node. We will use this to match /jump to this 
    // row later whenever we perform an update (TimeOut)
    dtRowClonesMap.set(data.student_no, rowNode);

    // Keep track of the last added row
    lastAddedRow = rowNode;

    countTotalAttendance();
    pushDistinctStudentId(data.student_no);
}
//
//
//
function onSuccessfulTimeout(data)
{
    var timeIn = data.time_in;
    var timeOut = data.time_out;
    var studentNo = data.student_no;

    if (!timeIn || !timeOut)
        return;

    var timeFormat = 'YYYY-MM-DD HH:mm:ss';

    timeIn = moment(timeIn, timeFormat);
    timeOut = moment(timeOut, timeFormat);

    // Check if timeOut is earlier than timeIn
    if (timeOut.isBefore(timeIn))
    {
        // Swap timeIn and timeOut
        var temp = timeIn;
        timeIn = timeOut;
        timeOut = temp;
    }

    var _duration = moment.duration(timeOut.diff(timeIn));
    var durationHrs = _duration.hours();
    var durationMin = _duration.minutes();

    var duration = `${durationHrs}h ${durationMin}m`;

    // If duration is less than a minute, we put Seconds instead
    if (durationHrs <= 0 && durationMin <= 0)
        duration = `${_duration.seconds()} secs`;

    // Find the cloneed row from the Map
    var rowNode = dtRowClonesMap.get(studentNo);

    if (!rowNode)
        return;
 
    // Set the timeout text
    $(rowNode).find('.time-out-label')
        .text(moment(data.time_out).format('h:mm A'))
        .addClass('time-out');

    // Remove hilight color of time in label
    $(rowNode).find('.time-in')
        .addClass('\:text-gray-600')
        .removeClass('time-in');
    
    // Calculate the duration
    $(rowNode).find('.duration-label').text(duration);

    // Update the timestamp to current timestamp in the cloned row
    // so that this stays at the very top of datable, given that 
    // default sorting is 'descending timestamp'
    $(rowNode).find('.row-timestamp').text(moment().format('YYYY-MM-DD HH:mm:ss'));

    // Remove the highlights of the previous rows
    if (lastAddedRow) 
    {
        $(lastAddedRow).removeClass('highlight-row-timein highlight-row-timeout');
    }

    // Remove the old row from the DataTable
    // (the old clone is exactly the same as what is the currently displayed in table)
    dataTable.row(dtRowClonesMap.get(studentNo)).remove();//.draw();
    
    // Then a highlight to the new row
    $(rowNode).addClass('highlight-row-timeout');

    // Add the updated row to the DataTable
    dataTable.row.add(rowNode).draw();

    // Keep track of last added row
    lastAddedRow = $(rowNode).get(0);

    // Finally, Remove the old row from the map
    dtRowClonesMap.delete(studentNo);
}
//
//
//
function countTotalAttendance()
{
    $('.total-records').text(dataTable.rows().count());
}
//
//
//
function countDistinctStudents()
{
    // Clear the student id set
    if (studentIdSet.size > 0)
        studentIdSet.clear();

    // Loop through the datatable rows
    dataTable.rows().every(function() 
    {
        var row = $(this.node());                   // get current <tr> row
        var studentNo = row.data('student-no');     // find the student no. attribute
        
        if (!studentIdSet.has(studentNo))           // add the student no onto the set
            studentIdSet.add(studentNo);
    });

    $('.total-students').text(studentIdSet.size);   // display total distinct students
}
//
//
//
function pushDistinctStudentId(studentNo)
{
    if (studentIdSet.has(studentNo))               // add the student no onto the set
        return;

    studentIdSet.add(studentNo);

    $('.total-students').text(studentIdSet.size);   // display total distinct students
}
//
// We expect this function to be called only once on page load.
// This function clones the TimedIn rows and adds an entry of
// each of them in the Map
//
function cloneTimedInRows()
{
    dtRowClonesMap.clear();                         // Always clear the map
 
    dataTable.rows().every(function() 
    {
        var row = $(this.node());                   // Get the current row DOM element

        if (row.hasClass('timed-in-rows')) 
        {   
            const key = row.data('student-no');     // The student number will be the map's key

            if (key)                                // Add an entry to the map if there is really a key
                dtRowClonesMap.set(key, row);  
        }
    });
}
// ------------------------------------------------------
/* #endregion ASYNCHRONOUS OPERATIONS */
// ------------------------------------------------------
