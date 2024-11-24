<?php
    require('../inc/db_config.php');
    require('../inc/essentials.php');
    adminLogin();

    if (isset($_POST['action']) && $_POST['action'] == 'get_general') {
        $q = "SELECT * FROM `settings` WHERE `sr_no`=?";
        $values = [1];
        $res = select($q, $values, "i");
        $data = mysqli_fetch_assoc($res);
        echo json_encode($data);
    }

    if (isset($_POST['action']) && $_POST['action'] == 'upd_general') { 
        $frm_data = filteration($_POST);
        $q = "UPDATE `settings` SET `site_title`=?, `site_about`=? WHERE `sr_no`=?";
        $values = [$frm_data['site_title'], $frm_data['site_about'], 1];
        $res = update($q, $values, 'ssi');
        echo $res;
    }

    if (isset($_POST['action']) && $_POST['action'] == 'upd_shutdown') {
        $frm_data = ($_POST['upd_shutdown'] == 0) ? 1 : 0;
        $q = "UPDATE `settings` SET `shutdown`=? WHERE `sr_no`=?";
        $values = [$frm_data, 1];
        $res = update($q, $values, 'ii');
        echo $res;
    }

    if (isset($_POST['action']) && $_POST['action'] == 'get_contacts') {
        $q = "SELECT * FROM `contact_details` WHERE `sr_no`=?";
        $values = [1];
        $res = select($q, $values, "i");
        $data = mysqli_fetch_assoc($res);
        echo json_encode($data);
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] == 'upd_contacts') { 
        $frm_data = filteration($_POST);
        $q = "UPDATE `contact_details` SET `address`=?, `gmap`=?, `pn1`=?, `pn2`=?, `email`=?, `fb`=?, `insta`=?, `tw`=?, `iframe`=? WHERE `sr_no`=?";
        $values = [$frm_data['address'], $frm_data['gmap'], $frm_data['pn1'],$frm_data['pn2'], $frm_data['email'], $frm_data['fb'], $frm_data['insta'], $frm_data['tw'], $frm_data['iframe'], 1];
        $res = update($q, $values, 'sssssssssi');
        echo $res; // Đảm bảo echo trả về "1" nếu cập nhật thành công
    }

    if (isset($_POST['action']) && $_POST['action'] == 'add_member') {
        $frm_data = filteration($_POST); // Lọc dữ liệu
        $img_r = uploadImage($_FILES['picture'], ABOUT_FOLDER); // Hàm upload ảnh
    
        if ($img_r == 'inv_img') {
            echo 'inv_img'; // Định dạng file không hợp lệ
            exit;
        } elseif ($img_r == 'inv_size') {
            echo 'inv_size'; // File quá lớn
            exit;
        } elseif ($img_r == 'upd_failed') {
            echo 'upd_failed'; // Lỗi upload
            exit;
        } else {
            // Chuẩn bị câu lệnh INSERT
            $q = "INSERT INTO `team_details`(`name`, `picture`) VALUES (?, ?)";
            $values = [$frm_data['name'], $img_r];
            $res = insert($q, $values, 'ss'); // Hàm insert sử dụng prepared statements
    
            // Kiểm tra kết quả truy vấn
            if ($res) {
                echo '1'; // Thành công
            } else {
                echo 'Query cannot executed - Insert'; // Lỗi khi thực thi
            }
            exit;
        }
    }
    
    

    if (isset($_POST['action']) && $_POST['action'] == 'get_member') {
        $res = selectAll('team_details'); // Tên bảng phải khớp với trong database
        while ($row = mysqli_fetch_assoc($res)) {
            $path = ABOUT_IMG_PATH;
            echo <<<data
                <div class="col-md-3 mb-3">
                    <div class="card" style="width: 18rem;">
                        <img src="$path$row[picture]" class="card-img-top">
                        <div class="card-body text-center">
                            <p class="card-text">$row[name]</p>
                            <button type="button" class="btn btn-danger btn-sm shadow-none" onClick="rem_member($row[sr_no])">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            data;
        }
        exit;
    }
    
    if (isset($_POST['action']) && $_POST['action'] == 'rem_member') {
        $rm_data = filteration($_POST);
        $values = [$rm_data['rem_member']];
        $pre_q = "SELECT * FROM `team_details` WHERE `sr_no` = ?";
        $res = select($pre_q, $values, 'i');
        $img = mysqli_fetch_assoc($res);
    
        if (deleteImage($img['picture'], ABOUT_FOLDER)) {
            $q = "DELETE FROM `team_details` WHERE `sr_no` = ?";
            $res = delete($q, $values, 'i');
            echo $res;
        } else {
            echo 0;
        }
        exit;
    }
    
?>
