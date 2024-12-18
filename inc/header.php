<?php 
session_start();
  require('admin/inc/db_config.php');
  require('admin/inc/essentials.php');
    // Câu lệnh SQL
    $contact_q = "SELECT * FROM `contact_details` WHERE 1";

    // Gọi hàm select với giá trị rỗng (vì không có tham số cần truyền vào SQL này)
    $contact_r = mysqli_fetch_assoc(select($contact_q, [], ''));
?>

<nav id="nav-bar" class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand me-5 fw-bold fs-3 h-font" href="index.php">TJ Hotel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active me-2" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="rooms.php">Rooms</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="facilities.php">Facilities</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="contact.php">Contact us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="about.php">About</a>
        </li>
      </ul>
      <div class="d-flex">
        <!-- Button trigger modal -->
        <?php if (isset($_SESSION['userLogin']) && $_SESSION['userLogin'] == true): ?>
          <!-- Nút Logout -->
          <a href="/csdl/ajax/logout.php" class="btn btn-outline-dark shadow-none me-lg-2 me-2">Logout</a>
        <?php else: ?>
          <!-- Nút Login & Register -->
          <button type="button" class="btn btn-outline-dark shadow-none me-lg-2 me-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
          <button type="button" class="btn btn-outline-dark shadow-none me-lg-2 me-3" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>
        <?php endif; ?>
    </div>
    </div>
  </div>
</nav>
<!-- Modal Login -->
<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form id="loginForm" method="POST">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center">
                <i class="bi bi-person-circle fs-3 me-2"></i> User Login
                </h5>
                <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input id="email" name="email" type="email" class="form-control shadow-none" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input id="password" name="password" type="password" class="form-control shadow-none" required>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <button type="submit" name="login" class="btn btn-dark shadow-none">LOGIN</button>
                    <a href="javascript: void(0)" class="text-secondary text-decoration-none">Forgot Password</a>
                </div>
            </div>
        </form>
    </div>
  </div>
</div>

<!-- Modal Register -->
<div class="modal fade" id="registerModal"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="register-form">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center">
                <i class="bi bi-person-lines-fill fs-2 me-2"></i> User Register
                </h5>
                <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span class="badge bg-light text-dark mb-3 text-wrap lh-base">
                    Note: Your  details must match with your ID (Aadhar card, passport, driving license., etc.)
                    that will be required dring during check-in.
                </span>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 ps-0">
                            <label class="form-label">Name</label>
                            <input name="name" type="text" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-6 p-0">
                            <label class="form-label">Email address</label>
                            <input name="email" type="email" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-6 ps-0">
                            <label class="form-label">Phone Number</label>
                            <input name="phonenum" type="number" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-6 p-0 mb-3">
                            <label class="form-label">Picture</label>
                            <input name="profile" type="file" accept=".jpg, .jpeg, .png, .webp" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-12 p-0 mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control shadow-none" rows="1" required></textarea>
                        </div>
                        <div class="col-md-6 ps-0">
                            <label class="form-label">Pincode</label>
                            <input name="pincode" type="number" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-6 p-0 mb-3">
                            <label class="form-label">Date of birthday</label>
                            <input name="dob" type="date" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-6 ps-0">
                            <label class="form-label">Password</label>
                            <input name="pass" type="password" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-6 p-0 mb-3">
                            <label class="form-label">Re-password</label>
                            <input name="cpass" type="password" class="form-control shadow-none" required>
                        </div>
                    </div>
                </div>
                <div class="text-center my-1">
                    <button type="submit" class="btn btn-dark shadow-none">REGISTER </button>
                </div>


                <!-- <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" class="form-control shadow-none">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="email" class="form-control shadow-none">
                </div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <button type="submit" class="btn- btn-dark shadow-none">LOGIN </button>
                    <a href="javascript: void(0)" class="text-secondary text-decoration-none">Forgot Password</a>
                </div> -->
                
            </div>
        </form>
    </div>
  </div>
</div>
<br><br>

<?php
if (isset($_POST['login'])) {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Kiểm tra email và mật khẩu
    $query = "SELECT * FROM `user_cred` WHERE `email` = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Đăng nhập thành công
            $_SESSION['userLogin'] = true;
            $_SESSION['userId'] = $user['id'];
            $_SESSION['userName'] = $user['name'];

            echo "<script>alert('Login Successful! Redirecting to dashboard...');</script>";
            echo "<script>window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Incorrect password!');</script>";
        }
    } else {
        echo "<script>alert('No user found with this email!');</script>";
    }
}
?>
