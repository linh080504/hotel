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
        $res = mysqli_query($con, "SELECT * FROM `rooms`");
        if (!$res) {
            die('Query failed: ' . mysqli_error($con));
        }
    
        $i = 1;
        $data = "";
        while ($row = mysqli_fetch_assoc($res)) {

            if($row['status'] == 1){
                $status = "<button onclick='toggle_status($row[id], 0)' class='btn btn-dark btn-sm shadow-none'>active</button>";
            }
            else {
                $status = "<button onclick='toggle_status($row[id], 1)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";
            }
            
            

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
                        <button type='button' onclick='editRoom({$row['id']})' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-room'>
                            <i class='bi bi-pencil-square'></i>
                        </button>
                        <button type='button' onclick='' class='btn btn-info shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#room-images'>
                            <i class='bi bi-images'></i>
                        </button>
                        <button type='button' onclick='' class='btn btn-danger shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#room-images'>
                            <i class='bi bi-trash'></i>
                        </button>
                    </td>
                </tr>
            ";
            $i++;
        }
        echo $data;
        exit;
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
            echo "Error updating room in database";
            exit;
        }
    
        // Xóa features và facilities cũ
        $delete_features = "DELETE FROM `room_features` WHERE `room_id`=?";
        $delete_facilities = "DELETE FROM `room_facilities` WHERE `room_id`=?";
        delete($delete_features, [$frm_data['id']], 'i');
        delete($delete_facilities, [$frm_data['id']], 'i');

    
        // Thêm features và facilities mới
        
        $q2 = "INSERT INTO `room_features`(`room_id`, `features_id`) VALUES (?, ?)";
        if ($stmt = mysqli_prepare($con, $q2)) {
            foreach ($features as $f) {
                mysqli_stmt_bind_param($stmt, 'ii', $frm_data['id'], $f);
                mysqli_stmt_execute($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            error_log('Error preparing insert for features');
        }

        // Thêm facilities mới
        $q3 = "INSERT INTO `room_facilities`(`room_id`, `facilities_id`) VALUES (?, ?)";
        if ($stmt = mysqli_prepare($con, $q3)) {
            foreach ($facilities as $f) {
                mysqli_stmt_bind_param($stmt, 'ii', $frm_data['id'], $f);
                mysqli_stmt_execute($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            error_log('Error preparing insert for facilities');
            }
    }
    
?>