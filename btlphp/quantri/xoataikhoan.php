<?php 
require('include/header.php');

// Kết nối database
$conn = mysqli_connect("127.0.0.1", "root", "2004", "ecommerceshop");

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Xử lý xóa admin
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $sql = "DELETE FROM admins WHERE id = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        $success = "Xóa tài khoản admin thành công!";
    } else {
        $error = "Lỗi: " . mysqli_error($conn);
    }
}

// Lấy danh sách admin
$sql = "SELECT * FROM admins";
$result = mysqli_query($conn, $sql);
?>

<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Danh Sách Admin</h1>
                        </div>

                        <?php
                        if (isset($success)) {
                            echo '<div class="alert alert-success">' . $success . '</div>';
                        }
                        if (isset($error)) {
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                        ?>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Loại</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . $row['name'] . "</td>";
                                            echo "<td>" . $row['email'] . "</td>";
                                            echo "<td>" . $row['type'] . "</td>";
                                            echo "<td>" . $row['status'] . "</td>";
                                            echo "<td>";
                                            echo "<a href='?delete=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Bạn có chắc muốn xóa?\")'>Xóa</a>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center'>Không có dữ liệu</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                      
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