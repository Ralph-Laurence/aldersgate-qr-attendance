/**
 * This is a helper class that wraps the Instascan.js QR Code scanner
 */
class QRCodeScanner
{
    constructor(elementId) 
    {
        this.domElement = $(elementId).get(0);

        this.qrScanner = new Instascan.Scanner({ video: this.domElement });
        
        // this.SLEEP_PROMISE_DELAY = 3000;

        // Begin: Event handling
        //
        this.ev__onScanResult           = null;
        this.ev__onCameraStarting       = null;
        this.ev__onCameraStarted        = null;
        this.ev__onCameraStartFailed    = null;
        this.ev__onCameraStopping       = null;
        this.ev__onCameraStopped        = null;
        this.ev__onCameraStopFailed     = null;

        this.qrScanner.addListener('scan', (content) => 
        {
            if (this.ev__onScanResult)
                this.ev__onScanResult(content);
        });
        //
        // End: Event handling

        this.__cameraInstances = [];
    }

    async GetCamerasAsync(onSuccess, onFail)
    {
        try 
        {
            // Always clear the camera instances
            this.__cameraInstances = [];

            // Get the device cameras
            var cameras = await Instascan.Camera.getCameras();
             
            // This is the data that must be returned to user such as the
            // Camera name and id, which will be used in parts of UI later
            var cameraDetails = [];

            if (cameras.length > 0)
            { 
                $.each(cameras, (index, camera) => 
                {
                    // We can list a camera device only once.
                    // Each camera is identified by its camera id
                    if (this.__cameraInstances.hasOwnProperty(camera.id))
                        return true;

                    cameraDetails.push({ 
                        deviceId: camera.id,
                        deviceName: camera.name
                    });

                    // Cache the camera object instance
                    this.__cameraInstances[camera.id] = camera;
                });

                // Pass the camera details into callback
                onSuccess(cameraDetails);
            } 
            else
            {
                onFail("No cameras were detected. Please connect a compatible camera and refresh the page.");
            }
        } 
        catch (error) 
        {
            onFail("An error has occurred while attempting to detect connected cameras. Please ensure that you have allowed your browser to access the camera.<br><br>If you need assistance, please contact the administrator.");
        }
    }

    Open(cameraId)
    {
        if (this.CheckInstance() === -1)
        {
            if (this.ev__onCameraStartFailed)
                this.ev__onCameraStartFailed("The scanner engine failed to initialize. Please reload the page and try again.");

            return;
        }

        if (this.CheckCameraExists(cameraId) === -1)
        {
            this.ev__onCameraStartFailed("Can't start camera with unknown device id. Please select a different camera then try again.");
            return;
        }

        var camera = this.__cameraInstances[cameraId];
        
        if (this.ev__onCameraStarting)
            this.ev__onCameraStarting();

        this.qrScanner.start(camera).then(/*async*/() => 
        { 
            if (this.ev__onCameraStarted)
                this.ev__onCameraStarted();

            // await this.delayPromise(this.SLEEP_PROMISE_DELAY)
            //     .then(() => 
            //     {
            //         if (this.ev__onCameraStarted)
            //             this.ev__onCameraStarted();
            //     });
        })
        .catch(err => 
        {
            if (this.ev__onCameraStartFailed)
                this.ev__onCameraStartFailed("Failed to start the selected camera because of an error. Please contact the administrator.");
        });
    }

    Close()
    {
        if (this.CheckInstance() === -1)
        {
            if (this.ev__onCameraStopFailed)
                this.ev__onCameraStopFailed("Sorry, the camera cannot be stopped because the scanner engine has not been initialized or the instance has already been destroyed. If you continue seeing this message, please contact the administrator.");

            return;
        }

        if (this.ev__onCameraStopping)
            this.ev__onCameraStopping();

        this.qrScanner.stop().then(/*async*/ (resolve) => 
        {
            // Always close the stream
            if (this.domElement.srcObject)
            {
                this.domElement.srcObject.getTracks().forEach(track => track.stop());
                this.domElement.srcObject = null;
            }

            // Wait for given delay then do action
            if (this.ev__onCameraStopped)
                this.ev__onCameraStopped();
            // await this.delayPromise(this.SLEEP_PROMISE_DELAY)
            //     .then(() => 
            //     {
            //         if (this.ev__onCameraStopped)
            //             this.ev__onCameraStopped();
            //     })
        })
        .catch(err => 
        {
            if (this.ev__onCameraStopFailed)
                this.ev__onCameraStopFailed("Failed to stop the camera because of an error. Please reload the page. If the issue persists, please contact the administrator.");
        });
    }

    /**
    * @property {function} CheckCameraExists Validate the selected camera to make sure that its object instance exists in the memory.
    *
    * @returns {number} Returns -1 on fail. Otherwise, returns the camera Id
    */
    CheckCameraExists(cameraId)
    {
        if (!this.__cameraInstances.hasOwnProperty(cameraId))
            return -1;

        return cameraId;
    }

    CheckInstance()
    {
        var noDOM = this.domElement.length == 0;
        var noInstance = (this.qrScanner === undefined || this.qrScanner === null);
        
        if (noDOM || noInstance)
            return -1;

        return 0;
    }

    OnCameraStarting(eventHandler) {
        this.ev__onCameraStarting = eventHandler;
    }

    OnCameraStarted(eventHandler) {
        this.ev__onCameraStarted = eventHandler;
    }

    OnCameraStartFailed(eventHandler) {
        this.ev__onCameraStartFailed = eventHandler;
    }

    OnCameraStopping(eventHandler) {
        this.ev__onCameraStopping = eventHandler;
    }
    
    OnCameraStopped(eventHandler) {
        this.ev__onCameraStopped = eventHandler;
    }

    OnCameraStopFailed(eventHandler) {
        this.ev__onCameraStopFailed = eventHandler;
    }

    OnScanResult(eventHandler) {
        this.ev__onScanResult = eventHandler;
    }
    //
    // A simple implementation of a sleep function that returns a 
    // promise that resolves after a specified number of milliseconds. 
    // This is a common pattern in JavaScript for delaying the execution of code.
    //
    delayPromise(millis)
    {
        return new Promise(resolve => setTimeout(resolve, millis));
    }
}