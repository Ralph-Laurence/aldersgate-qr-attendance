@extends('layouts.master')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/lib/jquery-ui/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/lib/jquery-ui/jquery-ui.structure.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/lib/jquery-ui/jquery-ui.theme.min.css') }}">
<style>
    .camera-scanner-wrapper {
        width: 470px;
        height: 380px;
        max-width: 470px;
        max-height: 380px;

        /* box-shadow: inset 0px 3px 0px 0px var(--base-accent-color) !important;
        -webkit-box-shadow: inset 0px 3px 0px 0px var(--base-accent-color) !important;
        -moz-box-shadow: inset 0px 3px 0px 0px var(--base-accent-color) !important; */
    } 

    .camera-selector {
        display: none;
    }

    #camera-view {
        object-fit: cover !important; 
        background-color: #F8F9FA;
        border: 1px solid #CCCCCC;
        border-radius: 4px;
    }

    .cap {
        min-height: 6px;
        height: 6px;
    }

    .ui-selectmenu-button.ui-button 
    {
        font-family: var(--base-font-condensed), 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        padding-top: 4px !important;
        padding-bottom: 4px !important;
        max-width: 180px !important;
    }
</style>
@endpush
 
@section('content')

{{-- BEGIN MASTER CONTENT --}}
<div class="master-content d-flex flex-column">

    {{-- USER GUIDE ALERT --}}
    <div class="w-100 text-center px-4 pt-2 font-accent">
        <h6>{{ 'How to use the QR code attendance system?' }}</h6>
        <div class="alert text-dark py-2" style="background-color: #ECE7CE !important;" role="alert">
            {{ 'Point your QR Code towards the camera to begin scanning. Your attendance details will be recorded on
            the attendance sheet.' }}
        </div>
    </div>
    {{-- USER GUIDE ALERT --}}

    {{-- BEGIN STACKABLE CONTENT WRAPPER --}}
    <div class="stackable-content-wrapper d-flex flex-column flex-xl-row flex-grow-1">

        {{-- BEGIN STACKABLE CONTENT -> CONTAINER FOR CAMERA SCANNER--}}
        <div class="d-flex stackable-content w-md-75 w-l-100 px-4 pb-4 pt-0 justify-content-center">
 
            <div class="camera-scanner-wrapper d-flex flex-column bg-white border rounded-3 overflow-hidden pb-2 ">
                
                <!-- TOP BORDER -->
                <div class="cap accent-bg-color"></div>

                <!-- CONTROL RIBBON -->
                <div class="camera-control-ribbon d-flex flex-row align-items-center p-2">
                    <h6 class="mb-0 me-auto"><i class="fas fa-camera me-1"></i> {{ "Camera" }}</h6>
                    
                    <button class="btn py-1 px-2 mx-2 flat-btn flat-btn-warning text-dark d-none btn-open-cam">{{ "Open" }}</button>
                    <button class="btn py-1 px-2 mx-2 flat-btn flat-btn-danger d-none btn-stop-cam">{{ "Stop" }}</button>

                    <select class="camera-selector">
                        <option selected disabled>{{ 'Select Camera' }}</option> 
                    </select>
                    <button class="btn py-1 px-2 ms-2 flat-btn flat-btn-default btn-refresh-cam-list" 
                            data-mdb-toggle="tooltip" title="Refresh camera list">
                        <i class="fas fa-rotate"></i>
                    </button>
                </div>

                <!-- CAMERA PREVIEW SURFACE -->
                <div class="video-wrapper flex-1-1-auto px-2">
                    <video class="w-100 h-100" autoplay id="camera-view"></video>
                </div>
                
            </div>

        </div>
        {{-- END STACKABLE CONTENT -> CONTAINER FOR CAMERA SCANNER--}}

        {{-- BEGIN STACKABLE CONTENT -> CONTAINER FOR ATTENDANCE SHEET--}}
        <div class="d-flex bg-warning w-100 flex-column-reverse flex-fill">TEST</div>
        {{-- END STACKABLE CONTENT -> CONTAINER FOR ATTENDANCE SHEET--}}

    </div>
    {{-- END STACKABLE CONTENT WRAPPER --}}

</div>

@push('scripts')
<script src="{{ asset('js/lib/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/lib/instascan/instascan.min.js') }}"></script>
<script>
    var videoSurface = undefined;
    var qrScanner = undefined; 
    var attachedCameras = [];
    let WAIT_SECS_SHOW_HIDE_CAMERA_BUTTONS = 3000;

    $(document).ready(function () 
    {
        // Initialization Block
        __onInitialize();

        // Event Handlers Binding
        __onHandleEvents();
    });
    //
    // Initialize elements and references here
    //
    function __onInitialize()
    {
        // Get the <video> surface element for rendering the camera
        videoSurface = $('#camera-view').get(0);
         
        // Initialize the camera selectmenu and disable it on load
        $(".camera-selector").selectmenu({ disabled: true });

        // Get the attached cameras on load
        getAttachedCameras();
    }
    //
    // Bind event handlers here
    //
    function __onHandleEvents()
    {
        // Refresh button on cameras list
        $(".btn-refresh-cam-list").on('click', () => getAttachedCameras());

        // "Open" c"am button
        $(".btn-open-cam").on('click', () => openSelectedCamera());
    }
    //
    // Summary: Detect all attached camera devices then list them on a select menu.
    // Also, cache an object instance of them in memory
    //
    function getAttachedCameras()
    {
        // Disable the select list
        showCameraSelectList(false);

        Instascan.Camera.getCameras().then(function(cameras)
        {
            if (cameras.length > 0)
            {
                // Clear the list of cameras before adding
                $(".camera-selector").empty();

                // Clear the cameras array
                attachedCameras = [];
                
                // Set default selected value to none
                $(".camera-selector").append($('<option>', 
                {
                    text: '{{ "Select Camera" }}',
                    selected: true
                }));

                // Add each camera to the list
                $.each(cameras, function(index, camera)
                {
                    $(".camera-selector").append($('<option>', 
                    {
                        value: camera.id,
                        text: camera.name
                    }));

                    // Cache the object reference into key value pair array
                    addCamera(camera);
                });
 
                // Rebuild the camera select menu
                // refreshCameraSelectMenu(false);
                $(".camera-selector").selectmenu({ 
                    disabled: false,
                    change: function(event, ui)
                    {
                        var value = $(".camera-selector option:selected").attr('value'); //event.target.value;

                        // Only show the "Open" button when there is a valid camera selected
                        if (value !== undefined)
                            showOpenCamButton(true);
                        else 
                            showOpenCamButton(false);
                    }
                }).selectmenu('refresh');

                // Enable the "Open Cam" button 
                showStopCamButton(false); 
            }
            else
            {
                console.error('No cameras found on this device.');
            }

            // Enable the camera select list
            showCameraSelectList(true);
        })
        .catch(function(e)
        {
            alert('Error accessing camera: ' + e);
        });
    }
    //
    // Rebuild the camera select menu with an option to disable it
    //
    function refreshCameraSelectMenu(disable) 
    {
        $(".camera-selector").selectmenu({ disabled: disable }).selectmenu('refresh');
    }
    //
    // Keep an object instance of every camera (Store them in memory)
    // because it wont work when serialized as json. We need the actual
    // object from the memory
    //
    function addCamera(camera)
    {
        if (attachedCameras.hasOwnProperty(camera.id))
            return;

        attachedCameras[camera.id] = camera;
    }
    //
    // Begin rendering the camera according to the camera id that was
    // selected from the selectmenu
    //
    function openSelectedCamera()
    {  
        // Hide the "Open" button
        showOpenCamButton(false);
        
        // Disable the camera select list
        showCameraSelectList(false);

        // Get the selected camera's Id from the selectmenu
        var selectedCamId = $('.camera-selector option:selected').attr('value');

        // Make sure that the camera object is cached before using
        if (!attachedCameras.hasOwnProperty(selectedCamId))
        {
            alert("Can't start camera");

            // Enable the camera select list
            showCameraSelectList(true);
            return;
        }
        
        // Always destroy the instance of old scanner
        createScanner();

        // Get a reference of the camera object from the array
        var camera = attachedCameras[selectedCamId];

        // Start the camera
        qrScanner.start(camera).then(result => 
        { 
            return delayPromise(WAIT_SECS_SHOW_HIDE_CAMERA_BUTTONS).then(() => 
            {
                // "Stop" cam button
                $(".btn-stop-cam")
                    .off('click')
                    .on('click', () => stopScanner(qrScanner))
                    .removeClass('d-none')
                    .show();
            });
        })
        .catch(err => 
        {
            alert(err.toString());
        });
    }
    //
    // Stop the scanner then close the camera device
    //
    function stopScanner(scanner)
    {
        if (scanner === undefined || scanner === null)
            return;

        showStopCamButton(false); 
 
        scanner.stop().then(result => 
        {
            // Stop streaming
            closeCameraDevice();
            
            // Hide the "Stop" button, wait for 3secs then show the "Open" button again
            return delayPromise(WAIT_SECS_SHOW_HIDE_CAMERA_BUTTONS)
                .then(() => 
                {
                    showOpenCamButton(true);
                    
                    // Enable the camera select list
                    showCameraSelectList(true);
                });
        })
        .catch(err => 
        {
            alert(err.toString());
        });
    }
    //
    // Stop the camera device from streaming
    //
    function closeCameraDevice() 
    {  
        // https://stackoverflow.com/a/54514807
        /*
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
        */
        if(!videoSurface.srcObject)
            return;

        videoSurface.srcObject.getTracks().forEach(track => {
            track.stop();
        });
        videoSurface.srcObject = null;
    }
    //
    // Create a new instance of QR Scanner
    //
    function createScanner()
    {
        // If there is an active instance of scanner, force stop it from 
        // scanning, unbind its events then destroy its object reference
        if (qrScanner)
        { 
            qrScanner.stop().then(result => closeCameraDevice() );

            if (qrScanner.listenerCount('scan') > 0)
                qrScanner.removeAllListeners();

            qrScanner = undefined;
        }

        // Create a new object of the scanner passing the <video> element in constructor
        qrScanner = new Instascan.Scanner({
            video: videoSurface
        });

        // Bind event listener that handles the logic after scan completes
        qrScanner.addListener('scan', function(content)
        {
            alert(content);
        }); 
    }

    function showOpenCamButton(show)
    {
        if (!show)
        {
            $(".btn-open-cam").hide();
            return;
        }

        $(".btn-open-cam").toggleClass('d-none', false).show(); 
    }

    function showStopCamButton(show)
    {
        if (!show)
        {
            $(".btn-stop-cam").hide();
            return;
        }

        $(".btn-stop-cam").show(); 
    }

    function showCameraSelectList(show)
    {
        if (!show)
        {
            refreshCameraSelectMenu(true);
            $(".btn-refresh-cam-list").toggleClass('disabled', true);
            return;
        }

        refreshCameraSelectMenu(false);
        $(".btn-refresh-cam-list").toggleClass('disabled', false);
    }

    function delayPromise(millis)
    {
        return new Promise(resolve => setTimeout(resolve, millis));
    }
</script>
@endpush 
@endsection