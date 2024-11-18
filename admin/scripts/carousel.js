
        // let carousel_s_form = document.getElementById('carousel_s_form');

        // let carousel_picture_inp = document.getElementById('carousel_picture_inp');

        // carousel_s_form.addEventListener('submit', function(e){
        //     e.preventDefault();
        //     add_image();
        // })
        // function get_carousel() {
        
        //     let xhr = new XMLHttpRequest();
        //     xhr.open("POST", "ajax/carousel_crud.php", true);
        //     xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        //     xhr.onload = function() {
        //         document.getElementById('carousel-data').innerHTML = this.responseText;
        //     }
        //     xhr.send('get_carousel');
        // }
        // function add_image(){
        //     let data = new FormData();
        //     data.append('picture', carousel_picture_inp.files[0]);
        //     data.append('add_image', ' ' );

        //     let xhr = new XMLHttpRequest();
        //     xhr.open("POST", "ajax/carousel_crud.php", true);

        //     xhr.onload = function() {
        //         var myModal = document.getElementById('carousel-s');
        // if (myModal) {
        //     let modal = bootstrap.Modal.getInstance(myModal);
        //     if (modal) {
        //         modal.hide();
        //     } else {
        //         console.error('Bootstrap modal instance not found!');
        //     }
        //     } else {
        //     console.error('Modal with ID "carousel-s" not found!');
        //     }
        //         if(this.responseText == 'inv_img') {
        //             alert('error', 'Only JPG and PNG are allowed')
        //         }else if (this.responseText == 'inv_size') {
        //             alert('error', 'Img less than 2mb')
        //         }else if (this.responseText == 'upd_failed') {
        //             alert('error', 'Img down failed. Server down')
        //         } else {
        //             alert('success', 'Image added! ');
            
        //             carousel_picture_inp.value= '';
        //             get_carousel();
        //         }
        //     }
        //     xhr.send(data);
        // }
        // // function add_image() {
        // //     let data = new FormData();
        // //     data.append('picture', carousel_picture_inp.files[0]);
        // //     data.append('add_image', ' ');

        // //     let xhr = new XMLHttpRequest();
        // //     xhr.open("POST", "ajax/carousel_crud.php", true);

        // //     xhr.onload = function () {
        // //         // Kiểm tra nếu phần tử modal tồn tại
        // //         var myModal = document.getElementById('carousel-s');
        // //         if (myModal) {
        // //             let modal = bootstrap.Modal.getInstance(myModal); // Khởi tạo modal từ phần tử DOM
        // //             modal.hide();  // Ẩn modal nếu tồn tại
        // //         } else {
        // //             console.error('Modal not found!');
        // //         }

        // //         if (this.responseText == 'inv_img') {
        // //             alert('error', 'Only JPG and PNG are allowed');
        // //         } else if (this.responseText == 'inv_size') {
        // //             alert('error', 'Img less than 2mb');
        // //         } else if (this.responseText == 'upd_failed') {
        // //             alert('error', 'Img down failed. Server down');
        // //         } else {
        // //             alert('success', 'New image added!');
        // //             carousel_picture_inp.value = '';
        // //             get_carousel();
        // //         }
        // //     }

        // //     xhr.send(data);
        // // }

        // function rem_image(val) {
        //     let xhr = new XMLHttpRequest();
        //     xhr.open("POST", "ajax/carousel_crud.php", true);
        //     xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        //     xhr.onload = function() {
        //         if(this.responseText == 1) {
        //             alert('success', 'Image removed!');
        //             get_carousel();
        //         } else { 
        //             alert('error', 'Server down')
        //         }
        //     }
        //     xhr.send('rem_carousel= '+val); 
        // }
        // window.onload = function() {
        //     get_carousel(); 
        // }
        let carousel_s_form = document.getElementById('carousel_s_form');
        let carousel_picture_inp = document.getElementById('carousel_picture_inp');
        
        // Xử lý sự kiện khi form carousel được submit
        carousel_s_form.addEventListener('submit', function(e) {
            e.preventDefault();
            add_image();
        });
        
        // Lấy danh sách ảnh trong carousel
        function get_carousel() {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/carousel_crud.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                document.getElementById('carousel-data').innerHTML = this.responseText;
            }
            xhr.send('get_carousel');
        }
        
        // Thêm ảnh vào carousel
        function add_image() {
            let data = new FormData();
            data.append('picture', carousel_picture_inp.files[0]);
            data.append('action', 'add_image');  // Đảm bảo tên action chính xác
        
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/carousel_crud.php", true);
            
            xhr.onload = function() {
                // Đóng modal nếu có
                var myModal = document.getElementById('carousel-s');
                if (myModal) {
                    let modal = bootstrap.Modal.getInstance(myModal);
                    if (modal) {
                        modal.hide();
                    } else {
                        console.error('Bootstrap modal instance not found!');
                    }
                } else {
                    console.error('Modal with ID "carousel-s" not found!');
                }
        
                // Xử lý phản hồi từ server
                if (this.responseText == 'inv_img') {
                    alert('error', 'Only JPG and PNG are allowed');
                } else if (this.responseText == 'inv_size') {
                    alert('error', 'Image must be less than 2MB');
                } else if (this.responseText == 'upd_failed') {
                    alert('error', 'Image upload failed. Server error');
                } else {
                    alert('success', 'Image added successfully!');
                    carousel_picture_inp.value = '';  // Reset input
                    get_carousel();  // Cập nhật danh sách ảnh
                }
            }
            xhr.send(data);
        }
        
        // Xóa ảnh trong carousel
        function rem_image(val) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/carousel_crud.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.responseText == '1') {
                    alert('success', 'Image removed successfully!');
                    get_carousel();  // Cập nhật lại danh sách ảnh
                } else {
                    alert('error', 'Server error');
                }
            }
            xhr.send('rem_image=' + val);  // Gửi ID ảnh cần xóa
        }
        
        // Khi trang tải xong, gọi hàm get_carousel để tải danh sách ảnh
        window.onload = function() {
            get_carousel(); 
        }
        