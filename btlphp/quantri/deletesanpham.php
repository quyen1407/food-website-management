<!-- xóa sản phẩm -->
<?php

//lay id goi den
$delid = $_GET['id'];

//ket noi csdl
require('../db/conn.php');
//tìm các ảnh và xóa
$sql1 = "select images from products where id=$delid";
$rs = mysqli_query($conn, $sql1);
$row = mysqli_fetch_assoc($rs);

//danh sách các ảnh 
$images_arr = explode(';', $row['images']);
// print_r($images_arr); exit;
//xoa cac ảnh tron thư mục
foreach($images_arr as $img){
    unlink($img);
}

//xoa du lieu san pham trong CSDL
$sql_str = "delete from products where id=$delid";
mysqli_query($conn, $sql_str);

//trở về trang liệt kê sản phẩm
header("location: listsanpham.php");