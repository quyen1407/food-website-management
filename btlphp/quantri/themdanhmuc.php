
<!-- TRANG CẤU HÌNH ĐỂ THÊM DANH MỤC-->
<?php 
require('include/header.php');
?>

<div class="container">

<div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
       <!-- Phần thêm danh mục  -->
        <div class="row">
            <div class="col-lg-12">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Thêm Danh Mục Sản Phẩm</h1>
                    </div>
                    <form class="user" method="post" action="adddanhmuc.php">                        
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user"
                            id="name" name="name" aria-describedby="emailHelp"
                            placeholder="Tên danh mục">
                    </div>
                    <button class="btn btn-primary">Tạo mới</button>
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
?>