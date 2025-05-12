<?php
require('../db/conn.php');

// Bật hiển thị lỗi để debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kiểm tra dữ liệu từ form
if (!isset($_POST['name']) || empty($_POST['name'])) {
    die("Vui lòng nhập tiêu đề");
}
if (!isset($_FILES['anh']) || $_FILES['anh']['error'] == UPLOAD_ERR_NO_FILE) {
    die("Vui lòng chọn ảnh đại diện");
}

// Lấy dữ liệu từ form
$name = $_POST['name'];
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
$sumary = $_POST['sumary'] ?? '';
$description = $_POST['description'] ?? '';
$danhmuc = $_POST['danhmuc'];

// Xử lý upload ảnh
$filename = $_FILES['anh']['name'];
$location = "uploads/news/" . uniqid() . $filename;
$extension = strtolower(pathinfo($location, PATHINFO_EXTENSION));
$valid_extensions = array("jpg", "jpeg", "png");

// Kiểm tra thư mục uploads/news/
$upload_dir = "uploads/news/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true); // Tạo thư mục nếu chưa tồn tại
}

// Kiểm tra lỗi upload và định dạng file
if ($_FILES['anh']['error'] !== UPLOAD_ERR_OK) {
    die("Lỗi upload file: " . $_FILES['anh']['error']);
}
if (!in_array($extension, $valid_extensions)) {
    die("Định dạng file không hợp lệ. Chỉ chấp nhận jpg, jpeg, png.");
}

// Upload ảnh
if (!move_uploaded_file($_FILES['anh']['tmp_name'], $location)) {
    die("Không thể upload ảnh lên server.");
}

// Sử dụng prepared statement để thêm vào database
$stmt = $conn->prepare("INSERT INTO `news` (`title`, `avatar`, `slug`, `sumary`, `description`, `newscategory_id`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
$stmt->bind_param("sssssi", $name, $location, $slug, $sumary, $description, $danhmuc);

if ($stmt->execute()) {
    header("location: ./danhsachtintuc.php");
    exit();
} else {
    echo "Lỗi: " . $stmt->error;
    // Nếu thêm database thất bại, xóa ảnh đã upload để tránh rác
    if (file_exists($location)) {
        unlink($location);
    }
}

$stmt->close();
$conn->close();
?>