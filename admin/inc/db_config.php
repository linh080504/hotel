<?php 

    $hname = 'localhost';
    $uname = 'root';
    $pass = '1234567890';
    $db = 'hotel';

    $con= mysqli_connect($hname, $uname, $pass, $db);

    if(!$con){
        die("Cannot Connect to Database".mysqli_connect_errno());
    }


    function filteration($data){
        foreach($data as $key => $value){
            $data[$key] = trim($value);
            $data[$key] = stripslashes($value);
            $data[$key] = htmlspecialchars($value);
            $data[$key] = strip_tags($value);    
        }
        return $data;
    }

    function select($sql, $value, $datatypes){
        $con = $GLOBALS['con'];
        if($stmt = mysqli_prepare($con, $sql)){
            mysqli_stmt_bind_param($stmt, $datatypes,...$value);
            if(mysqli_stmt_execute($stmt)){
                $res = mysqli_stmt_get_result($stmt);
                return $res;
            }else{
                mysqli_stmt_close($stmt);
                die("Query cannot executed - Select");
            }
            
        }else{
            die("Query cannot prepared - Select");
        }
    }
?>