var videoPlayer = undefined;
var scanner = undefined; 

var scannerVideoWrapperHeight;

$(document).ready(function () 
{
    // Find the <video> player element with having an id "camera-view"
    videoPlayer = $('#camera-view');
 
    // TASK: Set the attendance wrapper div equal to camera wrapper div's height
    // only when the camera scanner div height goes higher than the
    // attendance wrapper's height

    // Cache (Get) the original container heights
    var prevScannerHeight = $(".scanner-wrapper").height();
    var prevScannerWidth = $(".scanner-wrapper").width();

    var attendanceSheetHeight = $(".attendance-sheet-wrapper").height();

    // Detect for resize of browser window including its elements
    // $(window).on('resize', function ()
    // {
    //     // Cache (Get) the updated height of containers
    //     cameraScannerHeight = $(".scanner-wrapper").height();
    //     cameraScannerWidth = $(".scanner-wrapper").width();

    //     // Check if updated height is not the same as cached height.
    //     // If not, this means the element has been resized
    //     if (cameraScannerHeight !== prevScannerHeight || cameraScannerWidth !== prevScannerWidth)
    //     {
    //         // If the scanner camera's container height is greater than the
    //         // attendance sheet container's height, apply the scanner's container height
    //         // into the attendance sheet height. Then revert to original size if scanner's
    //         // height goes smaller than the attendance sheet
    //         if (cameraScannerHeight > attendanceSheetHeight)
    //             $(".attendance-sheet-wrapper").height(cameraScannerHeight);
    //         else
    //             $(".attendance-sheet-wrapper").height(attendanceSheetHeight);
    //     }

    //     // Update the previous height to the current height
    //     prevScannerHeight = cameraScannerHeight;
    //     prevScannerWidth = cameraScannerWidth; 
    // });
     
    // List all connected cameras when the page finished loading
    getConnectedCameras(MSG_CAMERA_NOT_ALLOWED, MSG_WHEN_NO_CAMERA_FOUND);

    // Show the initial date and time calendar after the page loads
    updateTime();
  
    $("#camera-list").selectmenu({ disabled: true }); 
}); 

function getConnectedCameras(msg_camerasNotAllowed, msg_nothingFound) 
{
    // Clear the list of cameras then set the default 'Select Camera'
    let camerasSelectList = $('#camera-list');

    $('#camera-list').find('option').remove().end();

    $("#camera-list").append(`<option selected disabled>${DROPDOWN_LABEL_SELECT_CAMERA}</option>`);

    // Get all attached cameras then append them onto the dropdown
    Instascan.Camera.getCameras().then(cameras => 
    {
        // If cameras length is > 0, that means there are cameras attached.
        if (cameras.length > 0) 
        { 
            // For every camera we found, append them into dropdown list. 
            $.each(cameras, (index, cam) => 
            {
                camerasSelectList.append($('<option>', 
                {
                    value: cam.id, 
                    text: cam.name
                }));
            });

            camerasSelectList.append($('<option>', {text: "CAMFROG"}));

            $("#camera-list").selectmenu({
                disabled: false,
                change: function (event, ui) 
                {  
                    if (ui.item.value)
                    { 
                        var camId = event.target.value;
                        var camera = cameras.find(cam => cam.id === camId);

                        $(".btn-open-camera").off('click');
                        $(".btn-open-camera").on('click', () => { openCamera(camera); });
                        $(".btn-open-camera").removeClass('d-none');
                    }
                    else
                        $(".btn-open-camera").addClass('d-none');
                }
            });

            //$('#camera-list').selectmenu('option', 'disabled', false).selectmenu('refresh');
            $(".btn-open-camera").removeClass('disabled');
        }
        else 
        {
            $('#camera-list').selectmenu('option', 'disabled', true).selectmenu('refresh');
            $(".btn-open-camera").addClass('disabled');

            showErrorDialog( msg_nothingFound );
        }
    })

    // Handle errors here
    .catch(err => 
    {
        // Convert the error object into readable message (string)
        var exception = err.toString();

        // "Camera Not Allowed" error message
        if (exception.includes("Cannot access video stream") || exception.includes("NotAllowedError"))
        {
            showErrorDialog(msg_camerasNotAllowed);
            return;
        }

        // Other error message
        showErrorDialog(exception);
    });
}

function openCamera(targetCamera)
{  
    // Make sure that the camera is valid
    if (!targetCamera || targetCamera === undefined)
    {
        showWarnDialog(MSG_CAM_DETECTED_NOT_CONNECTED);
        return;
    } 
 
    // Force stop the scanner. Then remove the instance of it.
    if (scanner)
    {
        stopScanner(scanner, videoPlayer);
        scanner = undefined;
    }
 
    // Create a new object instance of the scanner passing the video element in constructor
    scanner = new Instascan.Scanner({
        video: videoPlayer.get(0),
        mirror: false
    });

    // Bind event listener that handles the logic after scan completes
    $(scanner).off('scan').on('scan', function (content)
    {
        alert(content);
    });
  
    scanner.start(targetCamera)
    .then(() => 
    { 
        // scannerVideoWrapperHeight = $(".scanner-video").height();
    })
    .catch (error =>
    {
        if (error.toString().includes('Camera is not defined'))
            showErrorDialog(MSG_INVALID_CAM);
        else
            showErrorDialog(error.toString());
    
        stopScanner(scanner, videoPlayer); 
    }); 
}
  
function stopScanner(scanner, videoPlayer) 
{  
    scanner.stop().then(() => 
    {
        // https://stackoverflow.com/a/54514807
        var videoEl = document.getElementById('camera-view');
        // now get the steam 
        stream = videoEl.srcObject;
        // now get all tracks
        tracks = stream.getTracks();
        // now close each track by having forEach loop
        tracks.forEach(function (track)
        {
            // stopping every track
            track.stop();
        });
        // assign null to srcObject of video
        videoEl.srcObject = null;
    });
}

// Update and display the current date and time
function updateTime() 
{
    var dayName = moment().format("dddd"); // name of day from Monday ~ Sunday
    var currentTime = moment().format('h:mm A');
    var currentDate = moment().format('MMM. DD, YYYY');

    $(".attendance-calendar-dayname").text(dayName);
    $(".attendance-calendar-date").text(currentDate);
    $(".attendance-calendar-time").text(currentTime);

    // Refresh the date and time every 1 sec
    setTimeout(updateTime, 1000);
}