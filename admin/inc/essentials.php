<?php
define('SITE_URL', 'http://127.0.0.1/csdl/');
define('ABOUT_IMG_PATH', SITE_URL.'images/about/');
define('CAROUSEL_IMG_PATH', SITE_URL.'images/carousel/');
define('UPLOAD_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'].'/csdl/images/');
define('FACILITIES_IMG_PATH', SITE_URL.'images/facilities/' );
define('ROOMS_IMG_PATH', SITE_URL.'images/rooms/' );
define('USERS_IMG_PATH', SITE_URL.'images/users/'); // Thư mục cho hình ảnh người dùng


define('ABOUT_FOLDER', 'about');
define('CAROUSEL_FOLDER', 'carousel/');
define('FACILITIES_FOLDER', 'facilities/');
define('ROOMS_FOLDER', 'rooms/');
define('USERS_FOLDER', 'users/');

define("SENDGRID_API_KEY", "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");
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
    
        // Kiểm tra nếu không có file
        if (!isset($file['name']) || empty($file['name'])) {
            return 'no_file'; // Không có file
        }
    
        // Kiểm tra định dạng file
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), $valid_extensions)) {
            return 'inv_img'; // Không đúng định dạng
        }
    
        // Kiểm tra kích thước file
        if ($file['size'] > $max_size) {
            return 'inv_size'; // File quá lớn
        }
    
        // Đảm bảo tên file an toàn
        $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']); // Loại bỏ ký tự đặc biệt
        $full_folder_path = UPLOAD_IMAGE_PATH . $folder; // Đường dẫn đầy đủ đến thư mục đích
    
        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!file_exists($full_folder_path)) {
            if (!mkdir($full_folder_path, 0777, true)) {
                return 'dir_failed'; // Lỗi tạo thư mục
            }
        }
    
        $path = $full_folder_path . '/' . $file_name;
    
        // Di chuyển file tải lên đến thư mục đích
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

    function uploadUserImage($image)
{
    $valid_mime = ['image/jpeg', 'image/png', 'image/webp']; $img_mime = $image['type'];
    if(!in_array($img_mime,$valid_mime)){
    return 'inv_img'; //invalid image mime or format
    }
    else{ $ext = pathinfo($image['name'], PATHINFO_EXTENSION); 
    $rname = 'IMG_'.random_int(11111,99999).".jpeg";

    $img_path = UPLOAD_IMAGE_PATH.USERS_FOLDER.$rname;

    if($ext == 'png' || $ext == 'PNG') {
        $img = imagecreatefrompng($image['tmp_name']);
        }
        else if($ext == 'webp' || $ext == 'WEBP') {
        $img = imagecreatefromwebp($image['tmp_name']);
        }
        else { $img = imagecreatefromjpeg($image['tmp_name']);
        }

    if(imagejpeg($img,$img_path,75)) { 
        return $rname;
    }
    else{ 
        return 'upd_failed';
        }
    }
}
    
    

?>