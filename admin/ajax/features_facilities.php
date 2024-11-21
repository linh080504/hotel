<?php
    require('../inc/db_config.php');
    require('../inc/essentials.php');
    adminLogin();

    if (isset($_POST['action']) && $_POST['action'] == 'add_feature') {
        $frm_data = filteration($_POST); // Lọc dữ liệu

        $q = "INSERT INTO `features`(`name`) VALUE (?)";
        $values = [$frm_data['name']]; 
        $res = insert($q,$values, 's');
        echo $res;
    }
    
    if (isset($_POST['action']) && $_POST['action'] == 'get_feature') {
        $res = selectAll('features');
        $i=1;
        while ($row = mysqli_fetch_assoc($res)) {
            echo <<<data
                <tr>
                    <td>$i</td>
                    <td>$row[name]</td>
                    <td>
                     <button type="button" class="btn btn-danger btn-sm shadow-none" onClick="rem_feature($row[id])">
                          <i class="bi bi-trash"></i> Delete
                         </button>
                    </td>
                </tr>
            data;
            $i++;
        }
    }
    
    if (isset($_POST['action']) && $_POST['action'] == 'rem_feature') {
        $frm_data = filteration($_POST);
        $values = [$frm_data['rem_feature']];

        $q = "DELETE FROM `features` WHERE `id`=?";
        $res = delete($q,$values, 'i');
        echo $res;
    }
    
    if (isset($_POST['action']) && $_POST['action'] == 'add_facility') {
        $frm_data = filteration($_POST); // Lọc dữ liệu
        $img_r = uploadSVGImage($_FILES['icon'], FACILITIES_FOLDER); // Hàm upload ảnh
    
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
            $q = "INSERT INTO `facilities`(`icon`, `name`, `description`) VALUES (?, ?, ?)";
            $values = [$img_r, $frm_data['name'], $frm_data['desc']];
            $res = insert($q, $values, 'sss'); // Hàm insert sử dụng prepared statements
    
            // Kiểm tra kết quả truy vấn
            if ($res) {
                echo '1'; // Thành công
            } else {
                echo 'Query cannot executed - Insert'; // Lỗi khi thực thi
            }
            exit;
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'get_facilities') {
        $res = selectAll('facilities');
        $i=1;
        $path = FACILITIES_IMG_PATH;

        while ($row = mysqli_fetch_assoc($res)) {
            echo <<<data
                <tr class='align-middle'>
                    <td>$i</td>
                    <td><img src="$path$row[icon]" width="100px"></td>
                    <td>$row[name]</td>
                    <td>$row[description]</td>
                    <td>
                     <button type="button" class="btn btn-danger btn-sm shadow-none" onClick="rem_facility($row[id])">
                          <i class="bi bi-trash"></i> Delete
                         </button>
                    </td>
                </tr>
            data;
            $i++;
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'rem_facility') {
        $frm_data = filteration($_POST);
        $values = [$frm_data['rem_facility']];

        $pre_q = "SELECT * FROM `facilities` WHERE `id`= ?";
        $res = select($pre_q, $values, 'i');
        $img = mysqli_fetch_assoc($res);
    
        if (deleteImage($img['icon'], FACILITIES_FOLDER)) {
            $q = "DELETE FROM `facilities` WHERE `id`=?";
            $res = delete($q,$values, 'i');
            echo $res;
        } else {
            echo 0;
        }
    }
?>
