@extends('layouts.master')
@section('styles')
    <style>
        @media screen and (min-width: 776px) and (max-width: 1199px) {
            .scanner-wrapper {
                max-width: 700px;
                max-height: 420px; 
            }
        }
    </style>
@endsection
@php
    $footerBehaviour = 'floating';
@endphp
@section('content')
    <div class="w-100 text-center px-2 pt-2 font-accent">
        <h6>{{ 'How to use the QR code attendance system?' }}</h6>
        <div class="alert text-dark py-2" style="background-color: #ECE7CE !important;" role="alert">
            {{ 'Point your QR Code towards the camera to begin scanning. Your attendance details will be recorded on the attendance sheet.' }}
        </div>
    </div>
    <div class="d-flex flex-xl-row flex-column justify-content-center font-accent">
        <div class="flex-fill mb-auto mt-0 d-flex justify-content-center px-0 w-xl-50 w-100 px-md-5 px-lg-2 px-sm-4">

            <div class="scanner-wrapper d-flex w-100 w-md-auto flex-column bg-white border rounded overflow-hidden shadow-2-soft">
                <div class="cap accent-bg-color w-100" style="height: 6px;"></div>
                <div class="d-flex align-items-center p-2">
                    <div class="fs-6 flex-fill"><i class="fas fa-camera me-2"></i>{{ 'Camera' }}</div>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-mdb-toggle="dropdown" aria-expanded="false">
                            {{ 'Select Camera' }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </div>
                </div>

                <div class="scanner-video px-2 pb-2">
                    <video autoplay id="camera-view" class="border bg-dark m-0 w-100 h-100"></video>
                </div>
            </div>

        </div>
        {{-- <div class="bg-warning flex-fill center-flexx w-100 w-md-50 p-3"> 
        </div> --}}
        <div class="flex-fill w-100 w-xl-50 px-md-2">

            <div class="scanner-div d-flex flex-column bg-white border rounded overflow-hidden shadow-2-soft">
                <div class="cap primary-bg-color w-100" style="height: 6px;"></div>
                <div class="d-flex align-items-center p-2">
                    <div class="fs-6 flex-fill"><i class="fas fa-clock me-2"></i>{{ 'Attendance Sheet' }}</div>
                    <h6>Time</h6>
                </div>

                <div class="attendance-sheet-wrapper p-2">
                    <table class="table align-middle mb-0 bg-white">
                        <thead class="bg-light">
                            <tr>
                                <th>Name</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt=""
                                            style="width: 45px; height: 45px" class="rounded-circle" />
                                        <div class="ms-3">
                                            <p class="fw-bold mb-1">John Doe</p>
                                            <p class="text-muted mb-0">john.doe@gmail.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="fw-normal mb-1">Software engineer</p>
                                    <p class="text-muted mb-0">IT department</p>
                                </td>
                                <td>
                                    <span class="badge badge-success rounded-pill d-inline">Active</span>
                                </td>
                                <td>Senior</td>
                                <td>
                                    <button type="button" class="btn btn-link btn-sm btn-rounded">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://mdbootstrap.com/img/new/avatars/6.jpg" class="rounded-circle"
                                            alt="" style="width: 45px; height: 45px" />
                                        <div class="ms-3">
                                            <p class="fw-bold mb-1">Alex Ray</p>
                                            <p class="text-muted mb-0">alex.ray@gmail.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="fw-normal mb-1">Consultant</p>
                                    <p class="text-muted mb-0">Finance</p>
                                </td>
                                <td>
                                    <span class="badge badge-primary rounded-pill d-inline">Onboarding</span>
                                </td>
                                <td>Junior</td>
                                <td>
                                    <button type="button" class="btn btn-link btn-rounded btn-sm fw-bold"
                                        data-mdb-ripple-color="dark">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://mdbootstrap.com/img/new/avatars/7.jpg" class="rounded-circle"
                                            alt="" style="width: 45px; height: 45px" />
                                        <div class="ms-3">
                                            <p class="fw-bold mb-1">Kate Hunington</p>
                                            <p class="text-muted mb-0">kate.hunington@gmail.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="fw-normal mb-1">Designer</p>
                                    <p class="text-muted mb-0">UI/UX</p>
                                </td>
                                <td>
                                    <span class="badge badge-warning rounded-pill d-inline">Awaiting</span>
                                </td>
                                <td>Senior</td>
                                <td>
                                    <button type="button" class="btn btn-link btn-rounded btn-sm fw-bold"
                                        data-mdb-ripple-color="dark">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        // https://www.js-tutorials.com/javascript-tutorial/how-to-scan-qr-reader-using-javascript-and-html5/?expand_article=1
        const video = document.getElementById('camera-view');
        let scanner = new Instascan.Scanner({
            video: video
        });
        scanner.addListener('scan', function(content) {
            alert(content);
        });
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
                scanner.addListener('scan', function(content) {
                    alert('QR code scanned! Content: ' + content);
                    // You can perform additional actions with the scanned content here
                });
            } else {
                console.error('No cameras found on this device.');
            }
        }).catch(function(e) {
            alert('Error accessing camera: ' + e);
        });
    </script>
@endsection
