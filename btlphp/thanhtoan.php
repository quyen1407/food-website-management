<?php
session_start();
$is_homepage = false;

// Hiển thị tất cả lỗi để dễ dàng debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

$cart = [];
if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
}

require_once('./db/conn.php');

// Kiểm tra kết nối cơ sở dữ liệu
if (!$conn) {
    die("Lỗi kết nối: " . mysqli_connect_error());
}

// Debug: Hiển thị thông tin POST và giỏ hàng khi form được gửi
if (isset($_POST['btDathang'])) {
    echo "<div style='background: #f8f9fa; padding: 10px; margin: 10px 0; border: 1px solid #ddd;'>";
    echo "<h4>Thông tin Debug:</h4>";
    echo "Dữ liệu POST: <pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "Dữ liệu giỏ hàng: <pre>";
    print_r($cart);
    echo "</pre>";
    echo "</div>";
}

if (isset($_POST['btDathang']) && !empty($_SESSION['cart'])) {
    // Lấy thông tin khách hàng từ form với xử lý an toàn
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // Kiểm tra form
    if (empty($firstname) || empty($lastname) || empty($phone) || empty($email) || empty($address)) {
        $error_message = "Vui lòng điền đầy đủ thông tin";
    } else {
        try {
            // Tạo đơn hàng - thử cách đơn giản hơn trước
            $sql = "INSERT INTO orders (user_id, firstname, lastname, address, phone, email, status, created_at, updated_at) 
                    VALUES (null, '$firstname', '$lastname', '$address', '$phone', '$email', 'Processing', now(), now())";
            
            // Debug: Hiển thị câu lệnh SQL
            echo "<div style='background: #f8f9fa; padding: 10px; margin: 10px 0; border: 1px solid #ddd;'>";
            echo "SQL orders: " . $sql . "<br>";
            
            // Thực thi và kiểm tra thành công
            if (mysqli_query($conn, $sql)) {
                $last_order_id = mysqli_insert_id($conn);
                echo "Chèn đơn hàng thành công. ID: " . $last_order_id . "<br>";
                
                // Thêm chi tiết đơn hàng
                $order_success = true;
                foreach ($cart as $item) {
                    $product_id = mysqli_real_escape_string($conn, $item['id']);
                    $price = mysqli_real_escape_string($conn, $item['disscounted_price']);
                    $qty = mysqli_real_escape_string($conn, $item['qty']);
                    $total = $qty * $price;
                    
                    $sql_detail = "INSERT INTO order_details (order_id, product_id, price, qty, total, created_at, updated_at) 
                                  VALUES ($last_order_id, $product_id, $price, $qty, $total, now(), now())";
                    
                    echo "SQL order_details: " . $sql_detail . "<br>";
                    
                    if (!mysqli_query($conn, $sql_detail)) {
                        $order_success = false;
                        echo "Lỗi chèn chi tiết đơn hàng: " . mysqli_error($conn) . "<br>";
                        break;
                    }
                }
                
                echo "</div>";
                
                // Nếu tất cả đều thành công, xóa giỏ hàng và chuyển hướng
                if ($order_success) {
                    unset($_SESSION["cart"]);
                    echo "<script>alert('Đặt hàng thành công!'); window.location.href = 'thankyou.php';</script>";
                    exit(); // Quan trọng để ngăn thực thi tiếp
                } else {
                    // Nếu có lỗi khi chèn chi tiết, cố gắng xóa đơn hàng chính
                    $delete_sql = "DELETE FROM orders WHERE id = $last_order_id";
                    mysqli_query($conn, $delete_sql);
                    
                    $error_message = "Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại sau.";
                }
            } else {
                echo "Lỗi chèn đơn hàng: " . mysqli_error($conn) . "<br>";
                echo "</div>";
                $error_message = "Không thể tạo đơn hàng. Vui lòng thử lại sau.";
            }
        } catch (Exception $e) {
            $error_message = "Đã xảy ra lỗi: " . $e->getMessage();
        }
    }
}

require_once('nguoidung/header.php');
?>
<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Thanh toán</h2>
                    <div class="breadcrumb__option">
                        <a href="./index.html">Home</a>
                        <span>Thanh toán</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Checkout Section Begin -->
<section class="checkout spad">
    <div class="container">
        <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
        <?php endif; ?>

        <div class="checkout__form">
            <h4>Thông tin Khách hàng</h4>
            <form action="" method="post">
                <div class="row">
                    <div class="col-lg-8 col-md-6">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Họ & tên lót<span>*</span></p>
                                    <input type="text" name="firstname" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Tên<span>*</span></p>
                                    <input type="text" name="lastname" required>
                                </div>
                            </div>
                        </div>

                        <div class="checkout__input">
                            <p>Địa chỉ nhận hàng:<span>*</span></p>
                            <input type="text" placeholder="Địa chỉ" class="checkout__input__add" name="address" required>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Số điện thoại:<span>*</span></p>
                                    <input type="text" name="phone" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Email:<span>*</span></p>
                                    <input type="email" name="email" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="checkout__order">
                            <h4>Đơn hàng</h4>
                            <div class="checkout__order__products">Sản phẩm <span>Thành tiền</span></div>
                            <ul>
                                <?php
                                $total = 0;
                                if (!empty($cart)) {
                                    foreach ($cart as $item) {
                                        $total += $item['qty'] * $item['disscounted_price'];
                                ?>
                                    <li>
                                        <?= htmlspecialchars($item['name']) ?> <span>
                                            <?= number_format($item['disscounted_price'] * $item['qty'], 0, '', '.') . " VNĐ" ?>
                                        </span>
                                    </li>
                                <?php 
                                    }
                                } else {
                                ?>
                                    <li>Giỏ hàng trống</li>
                                <?php } ?>
                            </ul>
                            <div class="checkout__order__total">Tổng tiền: <span>
                                    <?= number_format($total, 0, '', '.') . " VNĐ" ?>
                                </span>
                            </div>

                            <button type="submit" class="site-btn" name="btDathang" <?= empty($cart) ? 'disabled' : '' ?>>
                                Đặt hàng
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Checkout Section End -->

<?php require_once('nguoidung/footer.php'); ?>