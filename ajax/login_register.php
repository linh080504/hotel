<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

if (isset($_POST['register'])) {
    $data = filteration($_POST);

    // Check if passwords match
    if ($data['pass'] !== $data['cpass']) {
        echo 'pass_mismatch';
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
        echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already';
        exit;
    }

    // Upload profile image
    $img = uploadUserImage($_FILES['profile']);
    if ($img == 'inv_img') {
        echo 'inv_img';
        exit;
    } elseif ($img == 'upd_failed') {
        echo 'upd_failed';
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
        1, // is_verified: True since we're skipping email verification
        1, // status: Active
        $data['email']
    ];

    if (insert($query, $values, 'ssssssssss')) {
        echo 1; // Success
    } else {
        echo 'ins_failed'; // Insert failed
    }
}
