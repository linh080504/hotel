<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Features & Facilities</title>
    <?php require('inc/links.php') ?>
</head>
<body class="bg-white">
    <?php require('inc/header.php') ?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">FEARTURES & FACILITIES</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Features</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#feature-s">
                                <i class="bi bi-plus-square"></i>Add
                            </button>
                        </div>
                        
                        <div class="table-responsive-md" style="height: 350 px; overflow-y: scroll ">
                            <table class="table table-hover border">
                                <thead>
                                    <tr class="bg-dark text-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="feature-data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Facilities</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#facility-s">
                                <i class="bi bi-plus-square"></i>Add
                            </button>
                        </div>
                        
                        <div class="table-responsive-md" style="height: 350 px; overflow-y: scroll ">
                            <table class="table table-hover border">
                                <thead>
                                    <tr class="bg-dark text-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Icon</th>
                                    <th scope="col">Name</th>
                                    <th scope="col" width="40%">Description</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="facilities-data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Feature modal -->
        <div class="modal fade" id="feature-s" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="feature_s_form">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Feature</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Name</label>
                                            <input type="text" name="feature_name" class="form-control shadow-none" required>
                                        </div>
                                       
                                    </div>
                                    <div class="modal-footer">
                                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                                    </div>
                                </div>
                            </form>  
                        </div>
        </div>

    <!-- Facility Modal --> 
        <div class="modal fade" id="facility-s" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="facility_s_form">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Facility</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" name="facility_name" class="form-control shadow-none" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Icon</label>
                                <input type="file" name="facility_icon" accept=".svg" class="form-control shadow-none" required>
                            </div>                       
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="facility_desc" class="form-control shadow-none" rows="3"></textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                        </div>
                    </div>
                </form>  
            </div>
        </div>

    <?php require('inc/scripts.php') ?>

    <script>
        let feature_s_form = document.getElementById('feature_s_form');
        let facility_s_form = document.getElementById('facility_s_form');


    feature_s_form.addEventListener('submit', function(e){
        e.preventDefault();
        add_feature();
    });

    function add_feature() {

    let data = new FormData();
    data.append('name', feature_s_form.elements['feature_name'].value);
    data.append('action', 'add_feature');
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_facilities.php", true);

    xhr.onload = function () {
        let myModal = document.getElementById('feature-s');
        let modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if(this.responseText==1)
    {
        arlert('success','New feature added');
        feature_s_form.elements['feature_name'].value='';
        get_feature();
    }
    else {
        arlert('Error','Sever Down!');
    }
        
    };
    xhr.send(data);
    }

    function get_feature() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status == 200) {
                document.getElementById('feature-data').innerHTML = this.responseText;
            } else {
                alert('Error: Failed to load team data!');
            }
        };
        xhr.send("action=get_feature");
    }

    function rem_feature(val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_facilities.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == "1") { // So sánh chuỗi "1"
            arlert('success', 'Feature removed!');
            get_feature();
        } 
        else if(this.responseText == 'room_added!'){
            arlert('error', 'Feature is added in room!');
        }
        
        else {
            arlert('error', 'Server issue.');
        }
    };
    xhr.send('action=rem_feature&rem_feature=' + val);
    }

    facility_s_form.addEventListener('submit', function (e) {
    e.preventDefault(); // Chặn hành động mặc định
    add_facility();
    });

    
    function add_facility() {
        let data = new FormData();
        data.append('name', facility_s_form.elements['facility_name'].value);
        data.append('icon', facility_s_form.elements['facility_icon'].files[0]);
        data.append('desc', facility_s_form.elements['facility_desc'].value);
        data.append('action', 'add_facility');

        console.log([...data]); // Hiển thị dữ liệu gửi đi

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/features_facilities.php", true);

        xhr.onload = function () {

            let myModal = document.getElementById('facility-s');
            let modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            if (this.responseText === 'inv_img') {
                alert('Only SVG images are allowed!');
            } else if (this.responseText === 'inv_size') {
                alert('Image should be less than 1MB!');
            } else if (this.responseText === 'upd_failed') {
                alert('Image upload failed. Server Down!');
            } else if (this.responseText === '1') {
                alert('New facility added!');
                facility_s_form.reset();
                get_facilities();
            } else {
                alert('Unknown error: ' + this.responseText);
            }
        };

        xhr.send(data);
    }


    function get_facilities() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_facilities.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status == 200) {
            document.getElementById('facilities-data').innerHTML = this.responseText;
        } else {
            alert('Error: Failed to load team data!');
        }
    };
    xhr.send("action=get_facilities");
    }

    function rem_facility(val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_facilities.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == "1") { // So sánh chuỗi "1"
            arlert('success', 'Facility removed!');
            get_facilities();
        } 
        else if(this.responseText == 'room_added!'){
            arlert('error', 'Facility is added in room!');
        }
        
        else {
            arlert('error', 'Server issue.');
        }
    };
    xhr.send('action=rem_facility&rem_facility=' + val);
    }
    
    window.onload = function(){
        get_feature();
        get_facilities();
    }
    </script>
</body>
</html>