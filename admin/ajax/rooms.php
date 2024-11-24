<?php
    require_once('../inc/db_config.php');
    require_once('../inc/essentials.php');    
    adminLogin();

    if (isset($_POST['action']) && $_POST['action'] == 'add_room') {

        $features = filteration(json_decode($_POST['features']));
        $facilities = filteration(json_decode($_POST['facilities']));
    
        $frm_data = filteration($_POST);
    
        $q1 = "INSERT INTO `rooms`(`name`, `area`, `price`, `quantity`, `adult`, `children`, `description`) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";

        $value = [$frm_data['name'], $frm_data['area'], $frm_data['price'], $frm_data['quantity'], $frm_data['adult'], $frm_data['children'], $frm_data['desc']];

        if(insert($q1, $value, 'siiiiis')){
            $flag = 1;
        }

        $room_id = mysqli_insert_id($con);

        $q2 = "INSERT INTO `room_facilities`(`room_id`, `facilities_id`) VALUES (?,?)";

        if ($stmt = mysqli_prepare($con, $q2)){
            foreach($facilities as $f){
                mysqli_stmt_bind_param($stmt, 'ii', $room_id, $f);
                mysqli_stmt_execute($stmt);
            }
            mysqli_stmt_close($stmt);
        }
        else{
            $flag = 0;
            die('query cannot be prepare - insert');
        }

        $q3 = "INSERT INTO `room_features`(`room_id`, `features_id`) VALUES (?,?)";

        if ($stmt = mysqli_prepare($con, $q3)){
            foreach($features as $f){
                mysqli_stmt_bind_param($stmt, 'ii', $room_id, $f);
                mysqli_stmt_execute($stmt);
            }
            mysqli_stmt_close($stmt);
        }
        else{
            $flag = 0;
            die('query cannot be prepare - insert');
        }

        if($flag){
            echo 1;
        }
        else{
            echo 0;
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'get_all_rooms') {
        $res = select("SELECT * FROM `rooms` WHERE `removed` = ?", [0], 'i');
    
        $i = 1;
        $data = "";
        while ($row = mysqli_fetch_assoc($res)) {
            $status = $row['status'] == 1
                ? "<button onclick='toggle_status({$row['id']}, 0)' class='btn btn-dark btn-sm shadow-none'>active</button>"
                : "<button onclick='toggle_status({$row['id']}, 1)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";
    
            $row_id = $row['id'];
            $row_name = addslashes($row['name']);
    
            $data .= "
                <tr class='align-middle'>
                    <td>{$i}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['area']} sq.ft.</td>
                    <td>
                        <span class='badge rounded-pill bg-light text-dark'>
                            Adult: {$row['adult']}
                        </span> <br>
                        <span class='badge rounded-pill bg-light text-dark'>
                            Children: {$row['children']}
                        </span>
                    </td>
                    <td> ₫{$row['price']}</td>
                    <td>{$row['quantity']}</td>
                    <td>$status</td>
                    <td>
                        <button type='button' onclick='editRoom({$row_id})' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-room'>
                            <i class='bi bi-pencil-square'></i>
                        </button>
                        <button type='button' onclick=\"room_images({$row_id}, '{$row_name}')\" class='btn btn-info shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#room-images'>
                            <i class='bi bi-images'></i>
                        </button>
                        <button type='button' onclick='remove_room({$row_id})' class='btn btn-danger shadow-none btn-sm'>
                            <i class='bi bi-trash'></i>
                        </button>
                    </td>
                </tr>
            ";
    
            $i++;
        }
        echo $data;
        exit();
    }
    

    if (isset($_POST['action']) && $_POST['action'] == 'toggle_status') {
        $frm_data = filteration($_POST);
    
        $q = "UPDATE `rooms` SET `status`=? WHERE `id`=?";
        $v = [$frm_data['value'], $frm_data['id']];
    
        if (update($q, $v, 'ii')) {
            echo 1; // Trả về 1 nếu thành công
        } else {
            echo 0; // Trả về 0 nếu thất bại
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'get_room') {
        $room_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    
        if (!$room_id) {
            echo json_encode(['error' => 'Invalid Room ID']);
            exit;
        }
    
        $query = "SELECT * FROM rooms WHERE id=?";
        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            echo json_encode(['error' => 'Query preparation failed']);
            exit;
        }
    
        mysqli_stmt_bind_param($stmt, 'i', $room_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $room = mysqli_fetch_assoc($result);
    
        if (!$room) {
            echo json_encode(['error' => 'Room not found']);
            exit;
        }
    
        // Lấy danh sách Features và Facilities
        $features = [];
        $facilities = [];
    
        $featureQuery = "SELECT features_id FROM room_features WHERE room_id=?";
        $stmt = mysqli_prepare($con, $featureQuery);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $room_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $features[] = $row['features_id'];
            }
        }
    
        $facilityQuery = "SELECT facilities_id FROM room_facilities WHERE room_id=?";
        $stmt = mysqli_prepare($con, $facilityQuery);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $room_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $facilities[] = $row['facilities_id'];
            }
        }
    
        // Trả về JSON dữ liệu phòng
        echo json_encode([
            'name' => $room['name'],
            'area' => $room['area'],
            'price' => $room['price'],
            'quantity' => $room['quantity'],
            'adult' => $room['adult'],
            'children' => $room['children'],
            'desc' => $room['description'],
            'features' => $features, // Mảng features đã chọn
            'facilities' => $facilities // Mảng facilities đã chọn
        ]);
        exit;
    }
    
    
    if (isset($_POST['action']) && $_POST['action'] == 'update_room') {
        $frm_data = filteration($_POST);
        $features = filteration(json_decode($_POST['features']));
        $facilities = filteration(json_decode($_POST['facilities']));
        
        // Cập nhật thông tin phòng
        $q1 = "UPDATE `rooms` SET 
                `name`=?, 
                `area`=?, 
                `price`=?, 
                `quantity`=?, 
                `adult`=?, 
                `children`=?, 
                `description`=?
               WHERE `id`=?";
        $values = [
            $frm_data['name'], 
            $frm_data['area'], 
            $frm_data['price'], 
            $frm_data['quantity'], 
            $frm_data['adult'], 
            $frm_data['children'], 
            $frm_data['desc'], 
            $frm_data['id']
        ];
    
        if (!update($q1, $values, 'siiiiisi')) {
            echo 0; // Trả về lỗi nếu không cập nhật được
            exit;
        }
    
        // Xóa các features và facilities cũ
        $delete_features = "DELETE FROM `room_features` WHERE `room_id`=?";
        $delete_facilities = "DELETE FROM `room_facilities` WHERE `room_id`=?";
        delete($delete_features, [$frm_data['id']], 'i'); // Thêm 'i'
        delete($delete_facilities, [$frm_data['id']], 'i'); // Thêm 'i'

    
        // Thêm các features và facilities mới
        $q2 = "INSERT INTO `room_features`(`room_id`, `features_id`) VALUES (?,?)";
        $q3 = "INSERT INTO `room_facilities`(`room_id`, `facilities_id`) VALUES (?,?)";
    
        foreach ($features as $f) {
            insert($q2, [$frm_data['id'], $f], 'ii');
        }
    
        foreach ($facilities as $f) {
            insert($q3, [$frm_data['id'], $f], 'ii');
        }
    
        echo 1; // Trả về thành công
        exit;
    }
    

    if (isset($_POST['action']) && $_POST['action'] == 'add_image') {
        $frm_data = filteration($_POST); // Lọc dữ liệu
        $img_r = uploadImage($_FILES['image'], ROOMS_FOLDER); // Hàm upload ảnh
    
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
            $q = "INSERT INTO `room_images`(`room_id`, `image`) VALUES (?, ?)";
            $values = [$frm_data['room_id'], $img_r];
            $res = insert($q, $values, 'ss'); // Hàm insert sử dụng prepared statements
    
            // Kiểm tra kết quả truy vấn
            if ($res) {
                echo '1'; // Thành công
            } else {
                echo 'Query cannot executed - Insert'; // Lỗi khi thực thi
            }

            echo $res;
            exit;
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'get_room_images') {
        $frm_data = filteration($_POST); // Lọc dữ liệu
    
        // Lấy danh sách hình ảnh
        $res = select("SELECT * FROM `room_images` WHERE `room_id` = ?", [$frm_data['id']], 'i');
        $path = ROOMS_IMG_PATH;
    
        // Hiển thị danh sách hình ảnh
        while ($row = mysqli_fetch_assoc($res)) {
            $thumb_btn = ""; // Khởi tạo biến trước vòng lặp
        
            if ($row['thumb'] == 1) {
                $thumb_btn = "<i class='bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>";
            }
            else{
                $thumb_btn = "<button onclick='thumb_image({$row['sr_no']}, {$row['room_id']})' class='btn btn-secondary btn-sm shadow-none'>
                        <i class='bi bi-check-lg'></i>
                    </button>";
            }
        
            echo <<<data
            <tr class='align-middle'>
                <td><img src='{$path}{$row['image']}' class='img-fluid'></td>
                <td>$thumb_btn</td>
                <td>
                    <button onclick='rem_image({$row['sr_no']}, {$row['room_id']})' class='btn btn-danger btn-sm shadow-none'>
                        <i class='bi bi-trash'></i>
                    </button>
                </td>
            </tr>
            data;
        }
        
    
        // Xử lý upload file nếu có file được gửi
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $img_r = uploadImage($_FILES['image'], ROOMS_FOLDER); // Gọi hàm upload
            if ($img_r == 'inv_img') {
                echo "Invalid file type.";
            } elseif ($img_r == 'inv_size') {
                echo "File size exceeds 2MB.";
            } elseif ($img_r == 'upd_failed') {
                echo "Failed to upload image.";
            } else {
                echo "Image uploaded successfully: $img_r";
            }
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'rem_image') {
        $rm_data = filteration($_POST);
        $values = [$rm_data['image_id'], $rm_data['room_id']];
        $pre_q = "SELECT * FROM `room_images` WHERE `sr_no` = ? AND `room_id` = ?";
        $res = select($pre_q, $values, 'ii'); // Sửa thành 'ii'
        $img = mysqli_fetch_assoc($res);
    
        // Kiểm tra nếu ảnh tồn tại
        if ($img && deleteImage($img['image'], ROOMS_FOLDER)) {
            // Xóa thông tin hình ảnh trong cơ sở dữ liệu
            $q = "DELETE FROM `room_images` WHERE `sr_no` = ? AND `room_id` = ?";
            $res = delete($q, $values, 'ii'); // Sửa thành 'ii'
            echo $res;  // Trả về kết quả của việc xóa ảnh
        } else {
            echo 0;  // Nếu không thể xóa ảnh
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'thumb_image') {
        $rm_data = filteration($_POST);
    
        // Đặt tất cả các ảnh của room_id thành thumb = 0
        $pre_q = "UPDATE `room_images` SET `thumb`=? WHERE `room_id` = ?";
        $pre_v = [0, $rm_data['room_id']];
        $pre_res = update($pre_q, $pre_v, 'ii');
    
        // Đặt ảnh được chọn thành thumb = 1
        $q = "UPDATE `room_images` SET `thumb`=? WHERE `sr_no` = ? AND `room_id` = ?";
        $v = [1, $rm_data['image_id'], $rm_data['room_id']];
        $res = update($q, $v, 'iii');
    
        echo $res;
    }
    
    if (isset($_POST['action']) && $_POST['action'] == 'remove_room') {
        $frm_data = filteration($_POST);
        $res1 = select("SELECT * FROM `room_images` WHERE `room_id` = ?", [$frm_data['room_id']], 'i');
        
        while($row = mysqli_fetch_assoc($res1)){
            deleteImage($row['image'], ROOMS_FOLDER);
        }
        
        $res2 = delete("DELETE FROM `room_images` WHERE `room_id` = ?", [$frm_data['room_id']], 'i');
        $res3 = delete("DELETE FROM `room_features` WHERE `room_id` = ?", [$frm_data['room_id']], 'i');
        $res4 = delete("DELETE FROM `room_facilities` WHERE `room_id` = ?", [$frm_data['room_id']], 'i');
        $res5 = update("UPDATE `rooms` SET `removed` = ? WHERE `id` = ?", [1, $frm_data['room_id']], 'ii');

        if($res2 || $res3 || $res4 || $res5){
            echo 1;
        }
        else{
            echo 0;
        }
        exit();
    }
    
    
?>