<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

session_start();

// Xử lý đăng ký
if (isset($_POST['register'])) {
    $data = filteration($_POST);

    // Check if passwords match
    if ($data['pass'] !== $data['cpass']) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match!']);
        exit;
    }

    // Check if user already exists
    $u_exist = select(
        "SELECT * FROM `user_cred` WHERE `email` = ? OR `phonenum` = ? LIMIT 1",
        [$data['email'], $data['phonenum']],
        "ss"
    );

    if (mysqli_num_rows($u_exist) != 0) {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        $message = ($u_exist_fetch['email'] == $data['email']) ? 'Email already exists!' : 'Phone number already exists!';
        echo json_encode(['status' => 'error', 'message' => $message]);
        exit;
    }

    // Upload profile image
    $img = uploadUserImage($_FILES['profile']);
    if ($img == 'inv_img') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid image file!']);
        exit;
    } elseif ($img == 'upd_failed') {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload image!']);
        exit;
    }

    // Encrypt password
    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

    // Insert user data into database
    $query = "INSERT INTO `user_cred` 
                (`name`, `address`, `phonenum`, `pincode`, `dob`, `profile`, `password`, `is_verified`, `status`, `datetime`, `email`) 
              VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";

    $values = [
        $data['name'], 
        $data['address'], 
        $data['phonenum'], 
        $data['pincode'], 
        $data['dob'], 
        $img, 
        $enc_pass, 
        1, // is_verified: True
        1, // status: Active
        $data['email']
    ];

    if (insert($query, $values, 'ssssssssss')) {
        echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to register user!']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request!']);
?>
