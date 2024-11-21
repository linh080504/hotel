<?php
define('SITE_URL', 'http://127.0.0.1/csdl/');
define('ABOUT_IMG_PATH', SITE_URL.'images/about/');
define('CAROUSEL_IMG_PATH', SITE_URL.'images/carousel/');
define('UPLOAD_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'].'/csdl/images/');
define('FACILITIES_IMG_PATH', SITE_URL.'images/facilities/' );


define('ABOUT_FOLDER', 'about');
define('CAROUSEL_FOLDER', 'carousel/');
define('FACILITIES_FOLDER', 'facilities/');


    function adminLogin() {
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        // Sử dụng header() để chuyển hướng
        header("Location: index.php");
        exit; // Kết thúc script sau khi chuyển hướng
    }
    session_regenerate_id(true);
}

    function redirect($url) {
        echo "
        <script>window.location.href ='$url';
        </script>";
    }
    function alert($type, $msg){
        $bs_class = ($type == "succeed")? "alert-succeed" : "alert-danger";
        echo <<<alert
                <div class="alert $bs_class alert-warning alert-dismissible fade show custom-alert" role="alert">
                    <strong class="me-3">$msg</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
         alert;
    }

    function uploadImage($file, $folder) {
        $valid_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $max_size = 2 * 1024 * 1024; // Giới hạn 2MB
    
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), $valid_extensions)) {
            return 'inv_img'; // Không đúng định dạng
        }
        if ($file['size'] > $max_size) {
            return 'inv_size'; // File quá lớn
        }
    
        $file_name = time() . '_' . $file['name']; // Đặt tên file
        $full_folder_path = UPLOAD_IMAGE_PATH . $folder; // Đường dẫn đầy đủ đến thư mục đích
    
        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($full_folder_path)) {
            mkdir($full_folder_path, 0777, true);
        }
    
        $path = $full_folder_path . '/' . $file_name;
    
        if (move_uploaded_file($file['tmp_name'], $path)) {
            return $file_name; // Thành công
        } else {
            return 'upd_failed'; // Lỗi upload
        }
    }
    
    

    function deleteImage($image, $folder) {
        $full_path = UPLOAD_IMAGE_PATH . $folder . '/' . $image;
    
        if (file_exists($full_path)) {
            return unlink($full_path);
        } else {
            return false; // File không tồn tại
        }
    }

    function uploadSVGImage($file, $folder) {
        $valid_mime_type = 'image/svg+xml';
        $max_size = 1 * 1024 * 1024; // Giới hạn 1MB
    
        // Kiểm tra MIME Type
        $mime = mime_content_type($file['tmp_name']);
        if ($mime !== $valid_mime_type) {
            return 'inv_img'; // Không đúng định dạng
        }
    
        // Kiểm tra kích thước file
        if ($file['size'] > $max_size) {
            return 'inv_size'; // File quá lớn
        }
    
        // Tạo tên file và thư mục đích
        $file_name = time() . '_' . basename($file['name']);
        $full_folder_path = UPLOAD_IMAGE_PATH . $folder;
    
        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($full_folder_path)) {
            mkdir($full_folder_path, 0777, true);
        }
    
        $path = $full_folder_path . '/' . $file_name;
    
        // Di chuyển file
        if (move_uploaded_file($file['tmp_name'], $path)) {
            return $file_name; // Thành công
        } else {
            return 'upd_failed'; // Lỗi upload
        }
    }
    
    

?>