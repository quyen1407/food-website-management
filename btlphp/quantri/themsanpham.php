
<!-- TRANG CẤU HÌNH ĐỂ THÊM SẢN PHẨM -->
<?php 
require('include/header.php')
?>

<!-- lấy từ form login -->
<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Thêm Mới Sản Phẩm</h1>
                        </div>
                        <!-- nhận dữ liệu và post dữ liệu, rồi trả lại trang liệt kê sản phẩm -->
                        <!-- bấm thêm sản phẩm thì nó sẽ chuyển qua form addproduct (sản phẩm) -->
                        <form class="user" method="post" action="addproduct.php" enctype="multipart/form-data">
                            <!-- form tên sản phẩm -->
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user"
                                    id="name" name="name" aria-describedby="emailHelp"
                                    placeholder="Tên Sản Phẩm">
                            </div>
                            <!-- form hình ảnh -->
                            <div class="form-group">
                                <label class="form-label">Hình ảnh sản phẩm: </label>
                                <input type="file" class="form-control form-control-user"
                                    id="anhs" name="anhs[]" multiple>
                            </div>
                            <!-- form tóm tắt sản phẩm -->
                            <div class="form-group">
                                <label class="form-label">Tóm Tắt Sản Phẩm: </label>
                                <textarea name="sumary" class="form-control"></textarea>
                            </div>
                            <!-- form mô tả sản phẩm -->
                            <div class="form-group">
                                <label class="form-label">Mô Tả Sản Phẩm: </label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4 mb-sm-0">
                                    <input type="text" class="form-control form-control-user"
                                        id="stock" name="stock" aria-describedby="emailHelp"
                                        placeholder="Số Lượng Nhập:">
                                </div>
                                <div class="col-sm-4 mb-sm-0">
                                    <input type="text" class="form-control form-control-user"
                                        id="giagoc" name="giagoc" aria-describedby="emailHelp"
                                        placeholder="Giá Bán:">
                                </div>
                                <div class="col-sm-4 mb-sm-0">
                                    <input type="text" class="form-control form-control-user"
                                        id="giaban" name="giaban" aria-describedby="emailHelp"
                                        placeholder="Giá Gốc:">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Danh Mục:</label>
                                <select class="form-control" name="danhmuc">
                                    <option>Chọn Danh Mục</option>
                                    <?php
                                    // lấy dữ liệu và load dữ liệu lên danh mục
                                    require('../db/conn.php');
                                    $sql_str = "select * from categories order by name";
                                    $result = mysqli_query($conn, $sql_str);
                                    while ($row = mysqli_fetch_assoc($result)){
                                    ?>
                                    <option value="<?php echo $row['id'];?>"> <?php echo $row['name'];?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <!-- load thương hiệu lên -->
                            <div class="form-group">
                                <label class="form-label">Thương Hiệu:</label>
                                <select class="form-control" name="thuonghieu">
                                    <option>Chọn Thương Hiệu</option>
                                    <?php
                                    // lấy dữ liệu và load dữ liệu lên thương hiệu
                                    require('../db/conn.php');
                                    $sql_str = "select * from brands order by name";
                                    $result = mysqli_query($conn, $sql_str);
                                    while ($row = mysqli_fetch_assoc($result)){
                                    ?>
                                    <option value="<?php echo $row['id'];?>"> <?php echo $row['name'];?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <button class="btn btn-primary">Thêm Mới</button>
                        </form>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
require('include/footer.php')
?>