<?php
define('UPLOAD_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'].'/csdl/images/');
define('ABOUT_FOLDER', 'about/');


    function adminLogin() {
        session_start();
        if(!(isset($_SESSION['adminLogin'])&& $_SESSION['adminLogin'] == true)){
            echo "
            <script>window.location.href ='index.php';
            </script>";
        };
        session_regenerate_id(true);
    }
    function redirect($url) {
        echo "
        <script>window.location.href ='$url';
        </script>";
    }
    function arlert($type, $msg){
        $bs_class = ($type == "succeed")? "alert-succeed" : "alert-danger";
        echo <<<alert
                <div class="alert $bs_class alert-warning alert-dismissible fade show custom-alert" role="alert">
                    <strong class="me-3">$msg</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
         alert;
    }

    function uploadImage($image, $folder){
        
    }

?>