//////////////////////////////////////////////////////////
// THIS SCRIPT MUST BE INCLUDED IN index.blade.php
//          OF QR CODE SCANNER PAGE.
//
// THE NAMING CONVENTION OF SCRIPTS WILL SCTRICTLY 
// FOLLOW LARAVEL's CONTROLLER NAMING CONVENTIONS
//-------------------------------------------------------
// CREATED: SEPTEMBER 29, 2023
//-------------------------------------------------------
//
/////////////////////////////////////////////////////////
//
// ------------------------------------------------------
/* #region BASE CODE */
// ------------------------------------------------------
//
var videoSurface = undefined;
var qrScanner = undefined;
var attachedCameras = [];
var studentIdsDistinct = undefined;
var dataTable = undefined;

var cameraSelect = ".camera-selector";
var openCameraBtn = ".btn-open-cam";
var stopCameraBtn = ".btn-stop-cam";
var refreshCamListButton = ".btn-refresh-cam-list";
var attendanceScrollView = '.attendance-scrollview';
var attendanceScrollViewRoot = '.attendance-scrollview-root';
var attendanceTable = ".attendance-table";

var FLAG_IS_CAM_OPEN = false;

const UPDATE_TICK = 1000; // 1second -> 1000millisec
const ON_START_STOP_STREAM_DELAY = 3000;

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
    studentIdsDistinct = new Set();

    // Get the <video> surface element for rendering the camera
    videoSurface = $('#camera-view').get(0);

    // Initialize the camera selectmenu and disable it on load
    $(cameraSelect).selectmenu({ disabled: true });

    // Create an instance of QR Scanner
    qrScanner = new Instascan.Scanner({ video: videoSurface });
 
    // Get the attached cameras on load
    getAttachedCameras();
 
    dataTable = new DataTable(attendanceTable, {
        "lengthChange": false,
        "searching": false,
        "autoWidth": false,
        stripeClasses: [] // disable all DataTables default styling
    }); 
    
}

// Handle events here
function __bindEvents() 
{
    $(refreshCamListButton).click(() => 
    {
        enableCamRefreshButton(false);
        getAttachedCameras();
        showOpenCamButton(false);
    });

    $(openCameraBtn).on('click', () => openSelectedCamera());
    $(stopCameraBtn).on('click', () => stopScanner(qrScanner));

    // Handle scan results
    qrScanner.addListener('scan', (content) => onScanResult(content));

    // Check user clicks onto the attendance table
    $(attendanceScrollViewRoot).click(() => {

        if (FLAG_IS_CAM_OPEN !== false)
            showWarnDialog("Please stop the camera to interact with this table.");
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
//
// Sleep async function (Promise) then execute action
//
function delayPromise(millis)
{
    return new Promise(resolve => setTimeout(resolve, millis));
}
// ------------------------------------------------------
/* #endregion BASE CODE */
// ------------------------------------------------------

// ------------------------------------------------------
/* #region BUSINESS LOGIC */
// ------------------------------------------------------

function updateTimeLabel()
{
    var hourMinute = moment().format("h:mm");
    var seconds = moment().format(":ss");
    var meridiem = moment().format("A");

    $(".hour-minute-label").text(hourMinute);
    $(".seconds-label").text(seconds);
    $(".day-night-label").text(meridiem);
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
    // Disable the select menu then clear its items
    rebuildSelectMenu(true);
    $(cameraSelect).empty();
    attachedCameras = [];

    // Set the default option to "Select Camera"
    addSelectOption('', 'Select Camera', true, true);

    Instascan.Camera.getCameras().then(function (cameras)
    {
        if (cameras.length > 0)
        {
            // List all cameras onto the dropdown list
            $.each(cameras, (index, camera) => addCamera(camera));

            // Rebuild the camera selectmenu then add an event handler
            // when a camera was selected from the menu
            rebuildSelectMenu(false, function (event, ui) 
            {
                var value = $(`${cameraSelect} option:selected`).attr('value');

                // Only show the "Open" button when there is a valid camera selected
                if (value !== undefined)
                    showOpenCamButton(true);
                else
                    showOpenCamButton(false);
            },
            () => enableCamRefreshButton());
        }
        else
        {
            // console.error('No cameras found.');
            showWarnDialog("No cameras were detected. Please connect a compatible camera and refresh the page.");
        }
    })
    .catch(function (e)
    {
        showErrorDialog("An error has occurred while attempting to get connected cameras. Please contact the administrator.");
        // console.error(e);
    });
}
//
// Append the camera to the select menu
//
function addCamera(camera)
{
    // Add camera to the select menu
    addSelectOption(camera.id, camera.name);

    // Cache the object reference of the camera
    if (attachedCameras.hasOwnProperty(camera.id))
        return;

    attachedCameras[camera.id] = camera;
}
//
// Add an <option> to a selectmenu
//
function addSelectOption(value, text, selected = false, disabled = false)
{
    $(cameraSelect).append($('<option>', {
        value: value,
        text: text,
        selected: selected,
        disabled: disabled
    }));
}
//
// Recreate the selectmenu. An optional callback function will
// execute after the selectmenu was successfully rebuilt
//
function rebuildSelectMenu(disabled = false, onSelectItem = null, callback = null)
{
    $(cameraSelect).selectmenu({
        disabled: disabled,
        change: function (event, ui)
        {
            if (onSelectItem !== null)
            {
                onSelectItem(event, ui);
            }
        }
    }).selectmenu('refresh');

    if (callback !== null)
        callback();
}
//
//
//
function showCameraSelectMenu(show)
{
    if (!show)
    {
        rebuildSelectMenu(true);
        enableCamRefreshButton(false);
        return;
    }

    rebuildSelectMenu(false);
    enableCamRefreshButton();
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
// Validate the selected camera to make sure that its object
// instance exists in the memory.
// 
// Returns -1 on fail. Otherwise, returns the selected Id
//
function validateSelectedCamera()
{
    // Get the selected camera's Id from the selectmenu
    var selectedCamId = $(`${cameraSelect} option:selected`).attr('value');

    // Make sure that the camera object is cached before using
    if (!attachedCameras.hasOwnProperty(selectedCamId))
    {
        return -1;
    }

    return selectedCamId;
}
//
// Begin rendering the camera according to the camera id that was
// selected from the selectmenu
//
function openSelectedCamera()
{
    showOpenCamButton(false);               // Hide the "Open" button
    showCameraPermsWarn(false);             // Hide the permissions warning
    showCameraSelectMenu(false);            // Disable the camera select menu

    var validatedCamId = validateSelectedCamera();

    if (validatedCamId == -1)
    {
        showWarnDialog("Can't start camera with unknown device id. Please select a different camera then try again.");

        // Enable the camera select menu
        showCameraSelectMenu(true);
        return;
    }

    // Get a reference of the camera object from the array
    var camera = attachedCameras[validatedCamId];

    // Start the camera
    qrScanner.start(camera).then(async result => 
    { 
        FLAG_IS_CAM_OPEN = true;

        // Lock the attendance sheet from interactions
        $(attendanceScrollView).toggleClass('pe-none', true);

        // Show crosshairs
        showCrosshairs(true);

        // Wait x Seconds then show the "Stop" button
        return delayPromise(ON_START_STOP_STREAM_DELAY).then(() => 
        {
            showStopCamButton(true);
        });
    })
    .catch(err => 
    {
        showErrorDialog("Can't start the selected camera because of an error. Please contact the administrator.");
        //alert(err.toString());
    });
}
//
// Stop the camera device from streaming
//
function closeCameraDevice() 
{
    if (!videoSurface.srcObject)
        return;

    videoSurface.srcObject.getTracks().forEach(track => track.stop());
    videoSurface.srcObject = null;
}
//
// Stop the scanner then close the camera device
//
function stopScanner()
{
    if (qrScanner === undefined || qrScanner === null)
        return;

    showStopCamButton(false);       // Hide the stop button
    showCrosshairs(false);          // Hide crosshairs 
    showCameraPermsWarn(true);      // then show camera permissions warn

    qrScanner.stop().then(async result => 
    { 
        // Stop streaming
        closeCameraDevice();

        // Wait for 3secs then do action
        return delayPromise(ON_START_STOP_STREAM_DELAY)
            .then(() => 
            {
                FLAG_IS_CAM_OPEN = false;

                // Unlock the attendance sheet from interactions
                $(attendanceScrollView).toggleClass('pe-none', false);

                showOpenCamButton(true);
                showCameraSelectMenu(true); // Enable the camera select menu
            });
    })
    .catch(err => 
    {
        showErrorDialog("Failed to stop the camera because of an error. Please contact the administrator.");
        // alert(err.toString());
    });
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

function onScanResult(content) 
{ 
    // Submit the scanned data to server
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(xhr) 
        {
            showErrorDialog("A fatal error has occurred. If this message continues to appear, please contact the system administrator.");
            console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
        }
    });

    $.ajax({
        url: SCAN_POST_URL,
        type: 'POST',
        data: {
            studentNo: content            
        },
        success: function (response) {  
            // console.log(response);
            processAttendanceViewData(response);
        }
    })
}

function processAttendanceViewData(viewData)
{
    /*
    {
  "msg": "success! The student id# is 00035",
  "status": "timed_in",
  "data": [
    {
      "firstname": "Bill",
      "middlename": "Henry",
      "lastname": "Gates",
      "student_no": "00035",
      "year": 2,
      "photo": "bill.png",
      "time_in": "2023-09-25 01:31:57"
    }
  ]
}
    */
    console.log(viewData);

    if (viewData)
    {
        var response = JSON.parse(viewData);
        var responseSuccessfulTimeIn = '0x5';
        var responseSuccessfulTimeOut = '0x6';

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

    timeIn = moment(timeIn).format('hh:mm A');      // Format the TimeIn string

    var rowData = 
    `<tr class="row-index-${data.student_no}" data-student-no="${ data.student_no }">
        <td class="td-name-details">
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
    </tr>`;

    $('.attendance-sheet').prepend(rowData); 

    // $('.total-records').text($('.attendance-sheet tr').length());
    var lblTotalRecords = $('.total-records').text();

    if (lblTotalRecords !== '' || lblTotalRecords !== undefined)
    {
        var total = parseInt(lblTotalRecords);
        total++;

        $('.total-records').text(total);
    }

    $('.attendance-sheet tr').each(function(){
        var studentNo = $(this).data('studentNo');
        studentIdsDistinct.add(studentNo);
    });

    $('.total-students').text(studentIdsDistinct.size);
}
//
//
//
function onSuccessfulTimeout(data)
{
    /*
    {
    "message": "Time out recorded",
    "status": "0x0",
    "data": {
        "time_in": "2023-09-26 10:11:20",
        "time_out": "2023-09-26 00:00:00",
        "student_no": "00002"
        }
    }
    */

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

    if (durationHrs <= 0 && durationMin <= 0)
        duration = `${_duration.seconds()} secs`;

    $(`.attendance-sheet .row-index-${studentNo}`).find('input.timeout-val');

    var inputs = $(`.attendance-sheet tr.row-index-${studentNo} td`)
        .find('input:hidden.timeout-val')
        .filter(function ()
        {
            return this.value === "";
        });

    if (inputs.length != 0)
    {
        // Create timeout label
        $(`.attendance-sheet .row-index-${studentNo} .time-out-label`)
            .text(moment(data.time_out).format('h:mm A'))
            .addClass('time-out');

        // Remove hilight color of time in label
        $(`.attendance-sheet .row-index-${studentNo} .time-in`)
            .addClass('\:text-gray-600')
            .removeClass('time-in');

        // Calculate the duration
        $(`.attendance-sheet .row-index-${studentNo} .duration-label`).text(duration);
    }
}
// ------------------------------------------------------
/* #endregion ASYNCHRONOUS OPERATIONS */
// ------------------------------------------------------