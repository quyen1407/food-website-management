<!-- đây là file xóa thương hiệu -->
<?php

//lấy id đc gọi đến
$delid = $_GET['id'];

//kết nối csdl
require('../db/conn.php');
// xóa trong bảng thương hiệu
$sql_str = "delete from brands where id=$delid";
mysqli_query($conn, $sql_str);

//trở về trang liệt kê thương hiệu
header("location: listbrands.php");