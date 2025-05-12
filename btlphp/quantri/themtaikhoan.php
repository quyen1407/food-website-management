<?php 
require('include/header.php');

// Kết nối database
$conn = mysqli_connect("127.0.0.1", "root", "2004", "ecommerceshop");

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Xử lý form khi submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Không mã hóa password, giữ nguyên giá trị thô
    // $hashed_password = md5($password); // Đã bỏ dòng này
    
    // Query insert
    $sql = "INSERT INTO admins (name, email, password, phone, address, type) 
            VALUES ('$name', '$email', '$password', '', '', 'Admin')";
    
    if (mysqli_query($conn, $sql)) {
        $success = "Tạo tài khoản admin thành công!";
    } else {
        $error = "Lỗi: " . mysqli_error($conn);
    }
}
?>

<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Phần thêm admin -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Thêm Tài Khoản Admin</h1>
                        </div>
                        
                        <?php
                        if (isset($success)) {
                            echo '<div class="alert alert-success">' . $success . '</div>';
                        }
                        if (isset($error)) {
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                        ?>

                        <form class="user" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <input type="text" 
                                       class="form-control form-control-user"
                                       id="name" 
                                       name="name" 
                                       placeholder="Tên admin" 
                                       required>
                            </div>
                            <div class="form-group">
                                <input type="email" 
                                       class="form-control form-control-user"
                                       id="email" 
                                       name="email" 
                                       placeholder="Email" 
                                       required>
                            </div>
                            <div class="form-group">
                                <input type="password" 
                                       class="form-control form-control-user"
                                       id="password" 
                                       name="password" 
                                       placeholder="Mật khẩu" 
                                       required>
                            </div>
                            <button type="submit" class="btn btn-primary">Tạo mới</button>
                        </form>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require('include/footer.php');

// Đóng kết nối
mysqli_close($conn);
?>