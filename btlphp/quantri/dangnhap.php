<?php
session_start();

// Kết nối database
$conn = mysqli_connect("127.0.0.1", "root", "2004", "ecommerceshop");

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Biến hiển thị thông báo lỗi
$error_message = "";

// Xử lý form khi submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $account_type = $_POST['account_type']; // "admin" hoặc "user"
    $remember = isset($_POST['remember']) ? 1 : 0;

    // Chọn bảng dựa trên loại tài khoản
    if ($account_type === "admin") {
        $sql = "SELECT * FROM admins WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Kiểm tra loại tài khoản (Admin/Staff)
            $type = $_POST['type']; // Admin hoặc Staff
            if ($user['type'] != $type) {
                $error_message = "Loại tài khoản không hợp lệ!";
            } else {
                // Kiểm tra mật khẩu
                if ($password == $user['password'] || password_verify($password, $user['password'])) {
                    if ($user['status'] == 'Active') {
                        // Lưu thông tin vào session
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['name'] = $user['name'];
                        $_SESSION['type'] = $user['type'];
                        $_SESSION['account_type'] = "admin";

                        // Ghi nhớ đăng nhập
                        if ($remember) {
                            setcookie("user_id", $user['id'], time() + (86400 * 30), "/");
                        }

                        // Điều hướng
                        header("Location: index.php");
                        exit();
                    } else {
                        $error_message = "Tài khoản của bạn đã bị khóa!";
                    }
                } else {
                    $error_message = "Mật khẩu không đúng!";
                }
            }
        } else {
            $error_message = "Email không tồn tại!";
        }
    } elseif ($account_type === "user") {
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Kiểm tra mật khẩu
            if (password_verify($password, $user['password'])) {
                // Lưu thông tin vào session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['account_type'] = "user";

                // Ghi nhớ đăng nhập
                if ($remember) {
                    setcookie("user_id", $user['id'], time() + (86400 * 30), "/");
                }
                
                // Điều hướng đến trang người dùng
                header("Location: ../index.php");
                exit();
            } else {
                $error_message = "Mật khẩu không đúng!";
            }
        } else {
            $error_message = "Email không tồn tại!";
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng Nhập Hệ Thống</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"><img src="chup.jpg.png" alt=""></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Chào Mừng Đến Hệ Thống!</h1>
                                    </div>
                                    <?php if (!empty($error_message)): ?>
                                        <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
                                    <?php endif; ?>
                                    <form class="user" method="POST" action="">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="email" placeholder="Nhập email..." required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password" placeholder="Mật khẩu" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck" name="remember">
                                                <label class="custom-control-label" for="customCheck">Ghi nhớ tôi</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="adminRadio" name="account_type" value="admin" class="custom-control-input" required>
                                                <label class="custom-control-label" for="adminRadio">Quản trị viên</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="userRadio" name="account_type" value="user" class="custom-control-input">
                                                <label class="custom-control-label" for="userRadio">Người dùng</label>
                                            </div>
                                        </div>
                                        <div id="adminType" class="form-group" style="display: none;">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="adminSubRadio" name="type" value="Admin" class="custom-control-input" checked>
                                                <label class="custom-control-label" for="adminSubRadio">Quản trị viên</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="staffSubRadio" name="type" value="Staff" class="custom-control-input">
                                                <label class="custom-control-label" for="staffSubRadio">Nhân viên</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Đăng Nhập</button>
                                        <div class="text-center mt-3">
                                            <a href="dangky.php" class="btn btn-primary btn-user btn-block" style="border-radius: 40px; padding: 8px">Đăng Ký</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <!-- <script>
        $(document).ready(function() {
            $('input[name="account_type"]').change(function() {
                if ($(this).val() === "admin") {
                    $("#adminType").show();
                } else {
                    $("#adminType").hide();
                }
            });
        });
    </script> -->
</body>
</html>