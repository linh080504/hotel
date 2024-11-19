<?php
    require('../inc/db_config.php');
    require('../inc/essentials.php');
    adminLogin();

    // Xử lý việc thêm hình ảnh vào carousel
    if (isset($_POST['action']) && $_POST['action'] == 'add_image') {
        
        $img_r = uploadImage($_FILES['picture'], CAROUSEL_FOLDER);

        // Kiểm tra lỗi khi tải hình ảnh lên
        if($img_r == 'inv_img'){
            echo $img_r;  // Nếu ảnh không hợp lệ
        }
        else if($img_r == 'inv_size'){
            echo $img_r;  // Nếu ảnh quá lớn
        }
        else if($img_r == 'upd_failed'){
            echo $img_r;  // Nếu không thể tải ảnh lên
        }
        else{
            // Nếu ảnh hợp lệ, thực hiện chèn vào cơ sở dữ liệu
            $q = "INSERT INTO `carousel`(`image`) VALUES (?)";
            $values = [$img_r];
            $res = insert($q, $values, 's');
            echo $res;  // Trả về kết quả của việc thêm ảnh
        }
    }
    // Lấy các hình ảnh từ carousel
    if(isset($_POST['action']) && $_POST['action'] == 'get_carousel') {
        $res = selectAll('carousel');
        while($row = mysqli_fetch_assoc($res)) {
            $path = CAROUSEL_IMG_PATH;
            echo <<<data
                <div class="col-md-4 mb-3">
                    <div class="card" style="width: 18rem;">
                        <img src="$path$row[image]" class="card-img-top">
                        <div class="card-body">
                            <button type="button" onClick="rem_image($row[sr_no])" class="btn btn-danger btn-sm shadow-none">
                                <i class="bi bi-trash"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            data;
        }
    }
    
    // Xóa hình ảnh trong carousel
    if(isset($_POST['action']) && $_POST['action'] == 'rem_image') {
        $rm_data = filteration($_POST);
        $values = [$rm_data['rem_image']];
        $pre_q = "SELECT * FROM `carousel` WHERE `sr_no = ?`";
        $res = select($pre_q, $values, 'i');
        $img = mysqli_fetch_assoc($res);

        // Xóa ảnh từ thư mục
        if(deleteImage($img['image'], CAROUSEL_FOLDER)){
            // Xóa thông tin hình ảnh trong cơ sở dữ liệu
            $q = "DELETE FROM `carousel` WHERE `sr_no = ?`";
            $res = delete($q, $values, 'i');
            echo $res;  // Trả về kết quả của việc xóa ảnh
        } else {
            echo 0;  // Nếu không thể xóa ảnh
        }
    }
?>