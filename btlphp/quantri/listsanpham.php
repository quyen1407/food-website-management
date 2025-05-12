<?php 
    require('include/header.php');
    function anhdaidien($arrstr,$height){
        //$arrstr là mảng các ảnh có dạng ảnh 1, ảnh 2, ảnh 3.
        //tách chuỗi thành mảng - tách với ";"
        // $arr = $arrstr.split(';');
        $arr = explode(';', $arrstr);
        return "<img src='$arr[0]' height='$height' />";
    }
?>

<div>
   <!--PHẦN DANH SÁCH SẢN PHẨM  -->
      <!-- DataTales Example -->
      <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Danh Mục Sản Phẩm</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <!-- cột hiện thị -->
                                        <tr>
                                            <th>Tên Sản Phẩm</th>
                                            <th>Hình Ảnh</th>
                                            <th>Danh Mục</th>
                                            <th>Thương Hiệu</th>
                                            <th>Trạng Thái</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                        <th>Tên Sản Phẩm</th>
                                            <th>Hình Ảnh</th>
                                            <th>Danh Mục</th>
                                            <th>Thương Hiệu</th>
                                            <th>Trạng Thái</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </tfoot>
                                    <!-- phần danh mục -->
                                    <tbody>
                                    <?php
                                    require('../db/conn.php');
                                    // lấy các bảng sản phẩm, danh mục sản phẩm, thương hiệu
                                    $sql_str = "select 
                                            products.id as pid,
                                            products.name as pname,
                                            images,
                                            categories.name as cname,
                                            brands.name as bname,
                                            products.status as pstatus
                                            from products, categories, brands 
                                            where products.category_id=categories.id and products.brand_id = brands.id 
                                            order by products.name";
                                    $result = mysqli_query($conn, $sql_str);
                                    while ($row = mysqli_fetch_assoc($result)){
                                    ?>
                                                                        
                                        <tr>
                                        <td><?=$row['pname']?></td>
                                        <td><?=anhdaidien($row['images'], "100px")?></td>
                                        <td><?=$row['cname']?></td>
                                        <td><?=$row['bname']?></td>
                                        <td><?=$row['pstatus']?></td>
                                        <td>
                                               <!-- xử lý phần sửa và xóa -->
                                               <a class="btn btn-warning" href="editsanpham.php?id=<?=$row['pid']?>">Sửa</a>  
                                                <a class="btn btn-danger" 
                                                href="deletesanpham.php?id=<?=$row['pid']?>"
                                                onclick="return confirm('Bạn chắc chắn xóa sản phẩm này?');">Xóa</a>
                                        </td>
                                        </tr>
                                        <?php

}
?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

</div>

<?php 
    require('include/footer.php');
?>