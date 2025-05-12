<!-- trang thêm sản phẩm -->
<?php
    // kết nối cơ sở dữ liệu và thêm sản phẩm
                    
    // lấy dữ liệu và load dữ liệu lên danh mục
    // lấy dữ liệu từ form sản phẩm
    require('../db/conn.php');
    $name = $_POST['name'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    $sumary = $_POST['sumary'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    $giagoc = $_POST['giagoc'];
    $giaban = $_POST['giaban'];
    $danhmuc = $_POST['danhmuc'];
    $thuonghieu = $_POST['thuonghieu'];
    $giaban = $_POST['giaban'];
    
    // xử lý hình ảnh
    $countfiles = count($_FILES['anhs']['name']);
    $imgs = '';
    for ($i = 0; $i < $countfiles; $i++) {
        $filename = $_FILES['anhs']['name'][$i];

        ## Vị trí lưu trữ
        $location = "uploads/".uniqid().$filename;
        // pathinfo ( string $path [, int $options = PATHINFO_DIRNAME | PATHINFO_BASENAME | PATHINFO_EXTENSION | PATHINFO_FILENAME ] ) : mixed
        $extension = pathinfo($location, PATHINFO_EXTENSION);
        $extension = strtolower($extension);
    
        ## Các định dạng file được phép upload
        $valid_extensions = array("jpg", "jpeg", "png");
    
        $response = 0;
        ## Kiểm tra định dạng file
        if (in_array(strtolower($extension), $valid_extensions)) {
    
            // thêm vào cơ sở dữ liệu - thêm thành công mới upload ảnh lên
            ## Upload file
            // $_FILES['file']['tmp_name']: Tên tệp tạm thời của file được lưu trữ trên server sau khi upload
            if (move_uploaded_file($_FILES['anhs']['tmp_name'][$i], $location)) {
    
                $imgs.=$location.";";
            }
        }
    
    }
    $imgs = substr($imgs, 0, -1);

    // câu lệnh thêm vào bảng
    $sql_str = "insert into `products` (`id`,`name`,`slug`,`description`,`summary`,`stock`,`price`,`disscounted_price`, `images`, `category_id`,`brand_id`,`status`,`created_at`,`updated_at`) values(null, '$name', '$slug','$description','$sumary',$stock, $giagoc,$giaban,'$imgs',$danhmuc,$thuonghieu,'Active',null,null);";
    
    // thực thi câu lệnh 
    // echo $sql_str; exit;
    mysqli_query($conn, $sql_str);

    // trở về trang
    header("location: listsanpham.php")
?>