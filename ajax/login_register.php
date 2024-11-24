<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
require("../inc/sendgrid/sendgrid-php.php");

function send_mail($uemail, $name, $token)
{
    $email = new \SendGrid\Mail\Mail(); 
    $email->setFrom("nguyenlethuylinh0805@gmail.com", "TJ_HOTEL");
    $email->setSubject("Account Verification Link");
    $email->addTo($uemail, $name);
    $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
    $email->addContent(
        "text/html",
        "
            Click the link to confirm you email: <br>
            <a href=".SITE_URL."email_confirm.php?email=$uemail&token=$token"."'>
                    CLICK ME
            </a>
        "
    );
    $sendgrid = new \SendGrid(SENDGRID_API_KEY);
        if($sendgrid->send($email)) {
            return 1;
        }
        else{
            return 0;
        }
}



if (isset($_POST['register'])) {
    $data = filteration($_POST);

    // Match password and confirm password field
    if ($data['pass'] != $data['cpass']) {
        echo 'pass_mismatch';
        exit;
    }

    // Check if user exists or not
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

    // Upload user image to server
    $img = uploadUserImage($_FILES['profile']);
    if ($img == 'inv_img') {
        echo 'inv_img';
        exit;
    } else if ($img == 'upd_failed') {
        echo 'upd_failed';
        exit;
    }

    function uploadUserImage($image)
    {
        $valid_mime = ['image/jpeg', 'image/png', 'image/webp']; 
        $img_mime = $image['type'];

        if (!in_array($img_mime, $valid_mime)) {
            return 'inv_img'; // Invalid image mime or format
        } else { 
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION); 
            $rname = 'IMG_'.random_int(11111, 99999).".jpeg";

            $img_path = UPLOAD_IMAGE_PATH . USERS_FOLDER . $rname;

            if (move_uploaded_file($image['tmp_name'], $img_path)) { 
                return $rname; // Thành công
            } else { 
                return 'upd_failed'; // Lỗi upload
            }
        }
    }

    $token = bin2hex(random_bytes(16));

        if(!send_mail($data['email'], $data['name'], $token)) {
            echo 'mail_failed';
            exit;
        }

        $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

        $query = "INSERT INTO `user_cred` (`name`, `address`, `phonenum`, `pincode`, `dob`, `profile`, `password`, `token`,`email`) VALUES (?,?,?,?,?,?,?,?,?)";

        $values = [$data['name'], $data['address'], $data['phonenum'], $data['pincode'], $data['dob'], $img, $enc_pass, $token, $data['email']];


        if(insert($query, $values, 'sssssssss')){
            echo 1;
        }
        else{
        echo 'ins_failed';
        }
}
?>
