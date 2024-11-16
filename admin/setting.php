<?php
require('inc/essentials.php');
adminLogin();
session_regenerate_id(true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Setting</title>
    <?php require('inc/links.php') ?>
</head>
<body class="bg-white">
    <?php require('inc/header.php') ?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Settings</h3>

                <!-- General setting section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">General Setting</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#general-s">
                                <i class="bi bi-pencil-square"></i>Edit
                            </button>
                        </div>
                        <h6 class="card-subtitle mb-1 fw-bold">Site Title</h6>
                        <p class="card-text" id="site_title"></p>
                        <h6 class="card-subtitle mb-1 fw-bold">About Us</h6>
                        <p class="card-text" id="site_about"></p>
                    </div>
                </div>

                <!-- General setting modal -->
                <div class="modal fade" id="general-s" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="general_s_form">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">General Setting</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Site Title</label>
                                        <input type="text" name="site_title" id="site_title_inp" class="form-control shadow-none" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">About Us</label>
                                        <textarea name="site_about" id="site_about_inp" class="form-control shadow-none" rows="6" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="loadGeneralData()" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                                </div>
                            </div>
                        </form>  
                    </div>
                </div>

                <!-- Shutdown section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Shutdown Website</h5>
                            <div class="form-check form-switch">
                                <input onchange="upd_shutdown(this.checked ? 1 : 0)" class="form-check-input" type="checkbox" id="shutdown-toggle">
                            </div>
                        </div>
                        <p class="card-text">
                            No customer will be allowed to book a room when shutdown mode is turned on.
                        </p>
                    </div>
                </div>

                <!-- Contact detail section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Contacts Setting</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#contacts-s">
                                <i class="bi bi-pencil-square"></i>Edit
                            </button>
                        </div>
                        <div class="row">
                            <div class="col lg-6">
                                <div class="mb-4">
                                    <h6 class="card-subtitle mb-1 fw-bold">Address</h6>
                                    <p class="card-text" id="address"></p>
                                </div>
                                <div class="mb-4">
                                    <h6 class="card-subtitle mb-1 fw-bold">Google Map</h6>
                                    <p class="card-text" id="gmap"></p>
                                </div>
                                <div class="mb-4">
                                    <h6 class="card-subtitle mb-1 fw-bold">Phone Numbers</h6>
                                    <p class="card-text mb-1">
                                        <i class="bi bi-telephone-fill"></i>
                                        <span id="pn1"></span>
                                    </p>
                                    <p class="card-text mb-1">
                                        <i class="bi bi-telephone-fill"></i>
                                        <span id="pn2"></span>
                                    </p>
                                </div>
                                <div class="mb-4">
                                    <h6 class="card-subtitle mb-1 fw-bold">E-mail</h6>
                                    <p class="card-text" id="email"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <h6 class="card-subtitle mb-1 fw-bold">Social links</h6>
                                    <p class="card-text mb-1">
                                        <i class="bi bi-facebook me-1"></i>
                                        <span id="fb"></span>
                                    </p>
                                    <p class="card-text mb-1">
                                        <i class="bi bi-instagram me-1"></i>
                                        <span id="insta"></span>
                                    </p>
                                    <p class="card-text mb-1">
                                        <i class="bi bi-twitter me-1"></i>
                                        <span id="tw"></span>
                                    </p>
                                </div>
                                <div class="mb-4">
                                    <h6 class="card-subtitle mb-1 fw-bold">iFrame</h6>
                                    <iframe id="iframe" class="border p-2 w-100" loading = "lazy"></iframe>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="card-subtitle mb-1 fw-bold">About Us</h6>
                        <p class="card-text" id="site_about"></p>
                    </div>
                </div>

                <!-- Contact details modal -->
                <div class="modal fade" id="contacts-s" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form id="contacts_s_form">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Conatct Setting</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="container-fluid p-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Address</label>
                                                    <input type="text" name="address" id="address_inp" class="form-control shadow-none" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Google Map Link</label>
                                                    <input type="text" name="gmap" id="gmap_inp" class="form-control shadow-none" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Phone Numbers (with country code)</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                                        <input type="text" name="pn1" id="pn1_inp" class="form-control shadow-none" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Phone Numbers (with country code)</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                                        <input type="text" name="pn2" id="pn2_inp" class="form-control shadow-none" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Email</label>
                                                    <input type="email" name="email" id="email_inp" class="form-control shadow-none" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Social Links</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="bi bi-facebook"></i></span>
                                                        <input type="text" name="fb" id="fb_inp" class="form-control shadow-none" required>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="bi bi-instagram"></i></span>
                                                        <input type="text" name="insta" id="insta_inp" class="form-control shadow-none" required>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="bi bi-twitter"></i></span>
                                                        <input type="text" name="tw" id="tw_inp" class="form-control shadow-none" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Iframe Src</label>
                                                    <input type="text" name="iframe" id="iframe_inp" class="form-control shadow-none" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="contacts_inp(contacts_data)" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                                </div>
                            </div>
                        </form>  
                    </div>
                </div> 
                
                <!-- Manager team section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Management Team</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#team-s">
                                <i class="bi bi-plus-square"></i>Add
                            </button>
                        </div>

                        <div class="row" id="team-data">

                        </div>

                <!-- Dream Team modal -->
                    <div class="modal fade" id="team-s" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="team_s_form">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Team Member</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Name</label>
                                            <input type="text" name="member_name" id="member_name_inp" class="form-control shadow-none" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Picture</label>
                                            <input type="file" name="member_picture" id="member_picture_inp" accept="[.jpg, .png, .webp, .jpeg]" class="form-control shadow-none" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                                    </div>
                                </div>
                            </form>  
                        </div>
                    </div>

                    <?php echo $_SERVER['DOCUMENT_ROOT']?>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php require('inc/scripts.php') ?>
    <script>
        let general_data, contacts_data;
        let general_s_form = document.getElementById('general_s_form');
        let contacts_s_form = document.getElementById('contacts_s_form');

        let team_s_form = document.getElementById('team_s_form');
        let member_name_inp = document.getElementById("member_name_inp");
        let member_picture_inp = document.getElementById("member_picture_inp");

        function loadGeneralData() {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (this.status == 200) {
                    general_data = JSON.parse(this.responseText);
                    document.getElementById('site_title').innerText = general_data.site_title;
                    document.getElementById('site_about').innerText = general_data.site_about;
                    document.getElementById('site_title_inp').value = general_data.site_title;
                    document.getElementById('site_about_inp').value = general_data.site_about;
                    // Sửa lỗi ở đây
                    let shutdownToggle = document.getElementById('shutdown-toggle');
                    shutdownToggle.checked = general_data.shutdown == 1; // True if shutdown is on
                    shutdownToggle.value = general_data.shutdown == 1 ? 1 : 0;
                }
            }
            xhr.send('action=get_general');
        }

        general_s_form.addEventListener('submit', function(e){
            e.preventDefault();
            upd_general(site_title_inp.value, site_about_inp.value);
        })

        function upd_general(site_title_val, site_about_val) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                let modal = bootstrap.Modal.getInstance(document.getElementById('general-s'));
                modal.hide();

                if (this.responseText == "1") {
                    arlert('success', 'Changes saved!');
                    loadGeneralData(); // Load updated data
                } else {
                    arlert('error', 'No changes saved!');
                }
            }
            xhr.send('action=upd_general&site_title=' + encodeURIComponent(site_title_val) + '&site_about=' + encodeURIComponent(site_about_val));
        }

        function upd_shutdown(val) {
            console.log("Shutdown value being sent: ", val); 
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                console.log(this.responseText);
                if (this.responseText == 1) {
                    arlert('success', 'Site has been shutdown!');
                } else {
                    arlert('success', 'Shutdown mode off');
                }
                loadGeneralData(); // Refresh data
            }
            xhr.send('action=upd_shutdown&shutdown=' + val);
        }

        function get_contacts() {

            let contacts_p_id = ['address', 'gmap', 'pn1', 'pn2', 'email', 'fb', 'insta', 'tw'];
            let iframe = document.getElementById('iframe');

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (this.status == 200) {
                    contacts_data = JSON.parse(this.responseText);
                    contacts_data = Object.values(contacts_data);
                    
                    for(i = 0; i < contacts_p_id.length; i++){
                        document.getElementById(contacts_p_id[i]).innerText = contacts_data[i+1];
                    }

                    iframe.src = contacts_data[9];
                    contacts_inp(contacts_data);
                }
            }
            xhr.send('action=get_contacts');
        }

        function contacts_inp(data){
            let contacts_inp_id = ['address_inp', 'gmap_inp', 'pn1_inp', 'pn2_inp', 'email_inp', 'fb_inp', 'insta_inp', 'tw_inp', 'iframe_inp'];

            for(i = 0; i < contacts_inp_id.length; i++){
                document.getElementById(contacts_inp_id[i]).value = data[i+1];
            }
        }

        contacts_s_form.addEventListener('submit', function(e){
            e.preventDefault();
            upd_contacts();
        })

        function upd_contacts() {
            let index = ['address', 'gmap', 'pn1', 'pn2', 'email', 'fb', 'insta', 'tw', 'iframe'];
            let contacts_inp_id = ['address_inp', 'gmap_inp', 'pn1_inp', 'pn2_inp', 'email_inp', 'fb_inp', 'insta_inp', 'tw_inp', 'iframe_inp'];
            let data_str = "";

            for (let i = 0; i < index.length; i++) {
                data_str += index[i] + "=" + encodeURIComponent(document.getElementById(contacts_inp_id[i]).value) + "&";
            }

            data_str += "action=upd_contacts"; // Thêm "action=" để chỉ định hành động

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                console.log(this.responseText);
                var myModal = document.getElementById('contacts-s');
                var modal = bootstrap.Modal.getInstance(myModal);
                modal.hide();

                if (this.responseText == "1") { // Sử dụng chuỗi "1" để so sánh
                    arlert('success', 'Changes saved!'); // Sửa lại tên hàm từ "arlert" thành "alert"
                    get_contacts();
                } else {
                    arlert('error', 'No changes made!');
                }
            }
            xhr.send(data_str);
        }

        team_s_form.addEventListener('submit', function(e){
            e.preventDefault();
            add_member();
        })

        function add_member(){
            let data = new FormData();
            data.append('name', member_name_inp.value);
            data.append('picture', member_picture_inp.files[0]);
            data.append('add_member', ' ' );

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);

            xhr.onload = function() {
                // let modal = bootstrap.Modal.getInstance(document.getElementById('general-s'));
                // modal.hide();

                // if (this.responseText == "1") {
                //     arlert('success', 'Changes saved!');
                //     loadGeneralData(); // Load updated data
                // } else {
                //     arlert('error', 'No changes saved!');
                // }
            }
            xhr.send(data);


        }
        window.onload = function() {
            loadGeneralData(); // Initial data load
            get_contacts(); 
        }
    </script>
</body>
</html>
