<?php
// Kết nối database
$conn = mysqli_connect("127.0.0.1", "root", "2004", "ecommerceshop");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Biến để hiển thị thông báo
$message = "";

// Xử lý form khi submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu

    // Kiểm tra email đã tồn tại chưa
    $check_sql = "SELECT * FROM user WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Email đã được sử dụng!";
    } else {
        // Thêm người dùng mới vào database
        $sql = "INSERT INTO user (id, name, email, password) VALUES (NULL, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $message = "Đăng ký thành công! Vui lòng đăng nhập.";
        } else {
            $message = "Đăng ký thất bại! Vui lòng thử lại.";
        }
        $stmt->close();
    }
    $check_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Đăng Ký Hệ Thống</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"><img src="chup.jpg.png" alt=""></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Đăng Ký Tài Khoản</h1>
                            </div>
                            <?php if (!empty($message)): ?>
                                <div class="alert <?php echo (strpos($message, 'thành công') !== false) ? 'alert-success' : 'alert-danger'; ?> text-center" role="alert">
                                    <?php echo $message; ?>
                                </div>
                            <?php endif; ?>
                            <form class="user" method="POST" action="">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="exampleName" name="name" placeholder="Tên của bạn" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail" name="email" placeholder="Địa chỉ email" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" id="exampleInputPassword" name="password" placeholder="Mật khẩu" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">Đăng Ký</button>
                            </form>
                            <div class="text-center mt-3">
                                <a href="dangnhap.php" class="btn btn-primary btn-user btn-block" style="border-radius: 40px; padding: 8px">Đăng Nhập</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>