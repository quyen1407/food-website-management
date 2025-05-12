<!-- TRANG THÊM THƯƠNG HIỆU -->
 <!-- TRANG NHẬN DỮ LIỆU TỪ FORM -->
 <?php

// echo "xin chao";


require('../db/conn.php');

//lay du lieu tu form
$name = $_POST['name'];
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));


// thêm vào bảng
$sql_str = "INSERT INTO `brands` (`name`, `slug`,  `status`) VALUES 
( '$name', 
'$slug', 
'Active');";

// echo $sql_str; exit;

//thực thi cậu lệnh
mysqli_query($conn, $sql_str);

//trở về trang
header("location: listbrands.php");
?>