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
                <h3 class="mb-4">ROOMS</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                    <div class="text-end mb-4">
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-room">
                                <i class="bi bi-plus-square"></i>Add
                            </button>
                        </div>
                        
                        <div class="table-responsive-lg" style="height: 450 px; overflow-y: scroll ">
                            <table class="table table-hover border text-center">
                                <thead>
                                    <tr class="bg-dark text-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">Guests</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="room-data">
                                </tbody>
                            </table>
                        </div>
                    </div>
               </div>
            </div>
        </div>
    </div>

    <!-- Add room modal -->
        <div class="modal fade" id="add-room" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <form id="add_room_form" autocomplete="off">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Room</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Name</label>
                                                <input type="text" name="name" class="form-control shadow-none" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Area</label>
                                                <input type="number" min="1" name="area" class="form-control shadow-none" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Price</label>
                                                <input type="number"  min="1" name="price" class="form-control shadow-none" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Quantity</label>
                                                <input type="number"  min="1" name="quantity" class="form-control shadow-none" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Adult (Max.)</label>
                                                <input type="number" min="1" name="adult" class="form-control shadow-none" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Children (Max.)</label>
                                                <input type="number" min="1" name="children" class="form-control shadow-none" required>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="form-label fw-bold">Features</label>
                                                <div class="row">
                                                    <?php 
                                                        $res = selectAll('features');
                                                        while($opt = mysqli_fetch_assoc($res)){
                                                            echo "
                                                                <div class='col-md-3 mb-1'>
                                                                    <label>
                                                                        <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none'>
                                                                        $opt[name]
                                                                    </label>
                                                                </div>
                                                            ";
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="form-label fw-bold">Facilities</label>
                                                <div class="row">
                                                    <?php 
                                                        $res = selectAll('facilities');
                                                        while($opt = mysqli_fetch_assoc($res)){
                                                            echo "
                                                                <div class='col-md-3 mb-1'>
                                                                    <label>
                                                                        <input type='checkbox' name='facilities' value='$opt[id]' class='form-check-input shadow-none'>
                                                                        $opt[name]
                                                                    </label>
                                                                </div>
                                                            ";
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="form-label fw-bold">Description</label>
                                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
                                            </div>
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

    
    <!-- Edit room modal -->
        <div class="modal fade" id="edit-room" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <form id="edit_room_form" autocomplete="off">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Room</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Name</label>
                                                <input type="text" name="name" class="form-control shadow-none" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Area</label>
                                                <input type="number" min="1" name="area" class="form-control shadow-none" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Price</label>
                                                <input type="number"  min="1" name="price" class="form-control shadow-none" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Quantity</label>
                                                <input type="number"  min="1" name="quantity" class="form-control shadow-none" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Adult (Max.)</label>
                                                <input type="number" min="1" name="adult" class="form-control shadow-none" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Children (Max.)</label>
                                                <input type="number" min="1" name="children" class="form-control shadow-none" required>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="form-label fw-bold">Features</label>
                                                <div class="row">
                                                    <?php 
                                                        $res = selectAll('features');
                                                        while($opt = mysqli_fetch_assoc($res)){
                                                            echo "
                                                                <div class='col-md-3 mb-1'>
                                                                    <label>
                                                                        <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none'>
                                                                        $opt[name]
                                                                    </label>
                                                                </div>
                                                            ";
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="form-label fw-bold">Facilities</label>
                                                <div class="row">
                                                    <?php 
                                                        $res = selectAll('facilities');
                                                        while($opt = mysqli_fetch_assoc($res)){
                                                            echo "
                                                                <div class='col-md-3 mb-1'>
                                                                    <label>
                                                                        <input type='checkbox' name='facilities' value='$opt[id]' class='form-check-input shadow-none'>
                                                                        $opt[name]
                                                                    </label>
                                                                </div>
                                                            ";
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="form-label fw-bold">Description</label>
                                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
                                            </div>
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


    <!-- Manager room images -->
    <div class="modal fade" id="room-images" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
            </div>
        </div>
    </div>

    <?php require('inc/scripts.php') ?>

<script>
    // Hàm xử lý thêm phòng
let add_room_form = document.getElementById('add_room_form');

add_room_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_rooms();
});

function add_rooms() {
    let data = new FormData();
    data.append('action', 'add_room');
    data.append('name', add_room_form.elements['name'].value);
    data.append('area', add_room_form.elements['area'].value);
    data.append('price', add_room_form.elements['price'].value);
    data.append('quantity', add_room_form.elements['quantity'].value);
    data.append('adult', add_room_form.elements['adult'].value);
    data.append('children', add_room_form.elements['children'].value);
    data.append('desc', add_room_form.elements['desc'].value);

    let features = [];
    Array.from(add_room_form.elements['features']).forEach(el => {
        if (el.checked) {
            features.push(el.value);
        }
    });

    let facilities = [];
    Array.from(add_room_form.elements['facilities']).forEach(el => {
        if (el.checked) {
            facilities.push(el.value);
        }
    });

    data.append('features', JSON.stringify(features));
    data.append('facilities', JSON.stringify(facilities));

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms.php", true);

    xhr.onload = function () {
        let myModal = document.getElementById('add-room');
        let modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        // Xóa backdrop nếu còn sót
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());

        if (this.responseText == 1) {
            arlert('Success', 'New room added.');
            add_room_form.reset();
            get_all_rooms();
        } else {
            arlert('Error', 'Server Down.');
        }
    };

    xhr.send(data);
}

// Hàm tải danh sách phòng
function get_all_rooms() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms.php", true);

    xhr.onload = function () {
        const roomData = document.getElementById('room-data');
        if (roomData) {
            roomData.innerHTML = this.responseText;
        } else {
            console.error("Element with ID 'room-data' does not exist in DOM.");
        }
    };

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send("action=get_all_rooms");
}

document.addEventListener("DOMContentLoaded", function () {
    get_all_rooms();
});

// Hàm thay đổi trạng thái phòng
function toggle_status(id, val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == 1) {
            arlert('Success' ,'Status toggled.');
            get_all_rooms();
        } else {
            arlert('Error', 'Server Down.');
        }
    };

    xhr.send('action=toggle_status&id=' + id + '&value=' + val);
}

// Hàm chỉnh sửa phòng
let edit_room_form = document.getElementById('edit_room_form');

function editRoom(roomId) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.status === 200) {
            let data = JSON.parse(this.responseText);

            if (!data || typeof data !== 'object') {
                console.error("Invalid data format:", data);
                return;
            }

            let form = document.getElementById("edit_room_form");
            form.elements['name'].value = data.name || '';
            form.elements['area'].value = data.area || '';
            form.elements['price'].value = data.price || '';
            form.elements['quantity'].value = data.quantity || '';
            form.elements['adult'].value = data.adult || '';
            form.elements['children'].value = data.children || '';
            form.elements['desc'].value = data.desc || '';

            // Gán `roomId` vào `data-room-id`
            form.setAttribute('data-room-id', roomId);

            // Đánh dấu các checkbox đã chọn cho Features
            let selectedFeatures = data.features || []; // Mảng `features` từ server
            Array.from(form.elements['features']).forEach(el => {
                el.checked = selectedFeatures.includes(parseInt(el.value)); // Kiểm tra nếu `el.value` nằm trong `selectedFeatures`
            });

            // Đánh dấu các checkbox đã chọn cho Facilities
            let selectedFacilities = data.facilities || []; // Mảng `facilities` từ server
            Array.from(form.elements['facilities']).forEach(el => {
                el.checked = selectedFacilities.includes(parseInt(el.value)); // Kiểm tra nếu `el.value` nằm trong `selectedFacilities`
            });
        } else {
            console.error("Error fetching room data: HTTP", this.status);
        }
    };

    xhr.send("action=get_room&id=" + roomId);
}

// Hàm cập nhật phòng
edit_room_form.addEventListener('submit', function (e) {
    e.preventDefault();
    updateRoom();
});

function updateRoom() {
    let form = document.getElementById('edit_room_form');
    let data = new FormData();

    data.append('action', 'update_room');
    data.append('id', form.getAttribute('data-room-id'));
    data.append('name', form.elements['name'].value);
    data.append('area', form.elements['area'].value);
    data.append('price', form.elements['price'].value);
    data.append('quantity', form.elements['quantity'].value);
    data.append('adult', form.elements['adult'].value);
    data.append('children', form.elements['children'].value);
    data.append('desc', form.elements['desc'].value);

    let features = [];
    Array.from(form.elements['features']).forEach(el => {
        if (el.checked) features.push(el.value);
    });
    data.append('features', JSON.stringify(features));

    let facilities = [];
    Array.from(form.elements['facilities']).forEach(el => {
        if (el.checked) facilities.push(el.value);
    });
    data.append('facilities', JSON.stringify(facilities));

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms.php", true);

    xhr.onload = function () {
        if (this.responseText == 1) {
            arlert('Success','Room updated successfully!');

            let editModal = bootstrap.Modal.getInstance(document.getElementById('edit-room'));
            if (editModal) {
                editModal.hide();
            }

            document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());

            get_all_rooms();
        } else {
            arlert('Error','Error updating room!');
        }
    };

    xhr.send(data);
}

window.onload = function () {
    get_all_rooms();
};

</script>
</body>
</html>