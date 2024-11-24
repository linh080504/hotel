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
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            const roomData = document.getElementById('room-data');
            if (roomData) {
                roomData.innerHTML = this.responseText; // Cập nhật danh sách phòng
            } else {
                console.error("Element with ID 'room-data' does not exist in DOM.");
            }
        };

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

        let add_image_form = document.getElementById('add_image_form');

        add_image_form.addEventListener('submit', function(e){
            e.preventDefault();
            add_image();
        });

        function add_image() {
                let data = new FormData();
                data.append('image', add_image_form.elements['image'].files[0]);
                data.append('room_id', add_image_form.elements['room_id'].value);
                data.append('action', 'add_image');  // Đảm bảo tên action chính xác
            
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/rooms.php", true);
                
                xhr.onload = function() {
            
                    // Xử lý phản hồi từ server
                    if (this.responseText == 'inv_img') {
                        arlert('error', 'Only JPG, WEBP or PNG are allowed', 'image-arlert');
                    } else if (this.responseText == 'inv_size') {
                        arlert('error', 'Image must be less than 2MB');
                    } else if (this.responseText == 'upd_failed') {
                        arlert('error', 'Image upload failed. Server error');
                    } else {
                        arlert('success', 'New image added!', 'image-arlert');
                        room_images(
                            add_image_form.elements['room_id'].value,
                            document.querySelector('#room-images .modal-title').innerText
                        );
                        add_image_form.reset();  // Reset input
                        
                    }
                }
                xhr.send(data);
        }

        function room_images(id, name) {
            console.log("Room ID:", id); // Kiểm tra ID
            console.log("Room Name:", name); // Kiểm tra Name

            const modalTitle = document.querySelector('#room-images .modal-title');
            if (modalTitle) {
                modalTitle.innerText = name; // Cập nhật tiêu đề modal
            } else {
                console.error("Modal title element not found.");
            }

            add_image_form.elements['room_id'].value = id; // Gán id phòng vào hidden input

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/rooms.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function () {
                document.getElementById('room-image-data').innerHTML = this.responseText;
            };

            xhr.send('action=get_room_images&id=' + id);
        }
        
        function rem_image(img_id, room_id){
            let data = new FormData();
                data.append('image_id', img_id);
                data.append('room_id', room_id);
                data.append('action', 'rem_image');  // Đảm bảo tên action chính xác
            
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/rooms.php", true);
                
            xhr.onload = function() {
            
                    // Xử lý phản hồi từ server
                if (this.responseText == 1) {
                    arlert('success', 'Image Removed!', 'image-arlert');
                    room_images(
                        room_id,
                        document.querySelector('#room-images .modal-title').innerText
                    );
                        
                } else {
                    arlert('error', 'Image removal failed', 'image-arlert');
                    }
                }
                xhr.send(data);
        }

        function thumb_image(img_id, room_id){
            let data = new FormData();
                data.append('image_id', img_id);
                data.append('room_id', room_id);
                data.append('action', 'thumb_image');  // Đảm bảo tên action chính xác
            
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/rooms.php", true);
                
            xhr.onload = function() {
                console.log("Server Response:", this.responseText); // Log phản hồi từ server

                // Xử lý phản hồi từ server
                if (this.responseText.trim() == "1") {
                    arlert('success', 'Image Thumbnail Changed!', 'image-arlert');
                    room_images(
                        room_id,
                        document.querySelector('#room-images .modal-title').innerText
                    );
                } else {
                    arlert('error', 'Thumbnail change failed', 'image-arlert');
                }
            };

                xhr.send(data);
        }

        function remove_room(room_id){
            
            if(confirm("Are you sure, you want to delete this room?")){
                let data = new FormData();
                data.append('room_id', room_id);
                data.append('action', 'remove_room');  // Đảm bảo tên action chính xác

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/rooms.php", true);
                    
                xhr.onload = function() {
                    console.log("Server Response:", this.responseText); // Log phản hồi từ server

                    // Xử lý phản hồi từ server
                    if (this.responseText.trim() == "1") {
                        arlert('success', 'Room Removed!');
                        get_all_rooms();
                    } else {
                        arlert('error', `Room removal failed. Server responded with: ${this.responseText}`);
                    }
                };


                    xhr.send(data);
            }
                
        }

        window.onload = function () {
            get_all_rooms();
        };

