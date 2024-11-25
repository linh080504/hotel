<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

if ($_POST['action'] == 'fetch_users') {
    $query = "SELECT `id`, `name`, `email`, `phonenum`, `address`, `pincode`, `dob`, `profile`, `status`, `datetime` FROM `user_cred`";
    $res = mysqli_query($con, $query);
    $data = '';
    while ($row = mysqli_fetch_assoc($res)) {
        $status = $row['status'] ? 'Active' : 'Inactive';
        $profile_image = $row['profile'] ? "<img src='images/users/{$row['profile']}' alt='Profile Image' width='50'>" : 'No Image';
        $data .= "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['phonenum']}</td>
            <td>{$row['address']}</td>
            <td>{$row['pincode']}</td>
            <td>{$row['dob']}</td>
            <td>{$profile_image}</td>
            <td>{$status}</td>
            <td>{$row['datetime']}</td>
            <td>
                <button onclick='deleteUser({$row['id']})' class='btn btn-danger btn-sm'>Delete</button>
            </td>
        </tr>";
    }
    echo $data;
}


if ($_POST['action'] == 'search_user') {
    $query = $_POST['query'];
    $res = mysqli_query($con, "SELECT * FROM `user_cred` WHERE `name` LIKE '%$query%' OR `email` LIKE '%$query%'");
    $data = '';
    while ($row = mysqli_fetch_assoc($res)) {
        $status = $row['status'] ? 'Active' : 'Inactive';
        $data .= "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['phonenum']}</td>
            <td>{$status}</td>
            <td>
                <button onclick='deleteUser({$row['id']})' class='btn btn-danger btn-sm'>Delete</button>
            </td>
        </tr>";
    }
    echo $data;
}

if ($_POST['action'] == 'add_user') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phonenum = $_POST['phonenum'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];

    $query = "INSERT INTO `user_cred` (`name`, `email`, `phonenum`, `password`, `address`, `pincode`, `status`, `datetime`) VALUES ('$name', '$email', '$phonenum', '$password', '$address', '$pincode', 1, NOW())";
    echo mysqli_query($con, $query) ? 1 : 0;
}

if ($_POST['action'] == 'delete_user') {
    $id = $_POST['id'];
    echo mysqli_query($con, "DELETE FROM `user_cred` WHERE `id`='$id'") ? 1 : 0;
}
?>
