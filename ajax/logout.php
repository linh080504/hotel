<?php
require('../admin/inc/essentials.php');
session_start();
session_unset(); // Xóa toàn bộ biến session
session_destroy(); // Hủy session
header("Location: ../index.php"); // Quay lại trang chính
exit;
?>