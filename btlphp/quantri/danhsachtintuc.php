<?php 
require('include/header.php');

function anhdaidien($arrstr, $height) {
    $arr = explode(';', $arrstr);
    $img_path = $arr[0];
    // Kiểm tra file tồn tại
    if (file_exists($img_path)) {
        return "<img src='$img_path' height='$height' />";
    } else {
        return "<span>Ảnh không tồn tại</span>";
    }
}
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh Sách Tin Tức</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tiêu đề</th>
                        <th>Ảnh đại diện</th>
                        <th>Danh mục</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                require('../db/conn.php');
                $sql_str = "SELECT news.*, news.id AS nid, newscategories.name 
                            FROM news 
                            JOIN newscategories ON news.newscategory_id = newscategories.id 
                            ORDER BY news.created_at DESC";
                $result = mysqli_query($conn, $sql_str);
                $stt = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $stt++;
                ?>
                    <tr>
                        <td><?=$stt?></td>
                        <td><?=$row['title']?></td>
                        <td><?=anhdaidien($row['avatar'], '100px')?></td>
                        <td><?=$row['name']?></td>
                        <td>
                            <a class="btn btn-warning" href="suatintucmoi.php?id=<?=$row['nid']?>">Sửa</a>  
                            <a class="btn btn-danger" 
                               href="xoatintucmoi.php?id=<?=$row['nid']?>" 
                               onclick="return confirm('Bạn chắc chắn xóa tin tức này?');">Xóa</a>
                        </td>
                    </tr>
                <?php } ?>                                
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require('include/footer.php'); ?>