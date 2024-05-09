<?php
//建立product藥妝商品RS
$maxRows_rs = 12; //分頁數量
$pageNum_rs = 0; //起始頁面 = 0
if (isset($_GET['pageNum_rs'])) {
    $pageNum_rs = $_GET['pageNum_rs'];
}
$startRow_rs = $pageNum_rs * $maxRows_rs;

if (isset($_GET['search_name'])){
    //使用關鍵字查詢  
    // LIKE 相似查詢 欄位名稱 LIKE '%' . keyword .'%'， '%'為任意字元
    $queryFirst = sprintf("SELECT * FROM product, product_img, pyclass 
    WHERE p_open=1 AND product_img.sort=1 AND product.p_id=product_img.p_id AND product.classid=pyclass.classid AND (product.p_name 
    LIKE '%s'  OR product.p_price LIKE '%s')
    ORDER BY product.p_id DESC", '%'.  $_GET['search_name'].'%', '%'.  $_GET['search_name'].'%');

}elseif(isset($_GET['level']) && $_GET['level'] == 1) {
    //使用第一層類別查詢
    $queryFirst = sprintf("SELECT * FROM product,product_img, pyclass 
    WHERE p_open=1 AND product_img.sort=1 AND product.p_id=product_img.p_id AND product.classid=pyclass.classid AND pyclass.uplink='%d' 
    ORDER BY product.p_id DESC", $_GET['classid']);

}elseif (isset($_GET['classid'])) {
    //使用產品類別查詢
    $queryFirst = sprintf("SELECT * FROM product,product_img 
    WHERE p_open=1 AND product_img.sort=1 AND product.p_id=product_img.p_id AND product.classid='%d' 
    ORDER BY product.p_id DESC", $_GET['classid']);

} else {
    //列出產品product查詢
    $queryFirst = sprintf("SELECT * FROM product,product_img 
    WHERE p_open=1 AND product_img.sort=1 AND product.p_id=product_img.p_id 
    ORDER BY product.p_id DESC", $maxRows_rs);

}
$query = sprintf("%s LIMIT %d,%d", $queryFirst, $startRow_rs, $maxRows_rs);
$pList01 = $link->query($query);
$i = 1; // 控制每列row產生
?>
<?php if($pList01->rowCount() != 0) { ?>


<?php while ($pList01_Rows = $pList01->fetch()) { ?>
    <?php if ($i % 4 == 1) { ?><div class="row text-center"><?php } ?>
        <div class="card col-md-3">
            <img src="product_img/<?php echo $pList01_Rows['img_file']; ?>" class="card-img-top" alt="<?php echo $pList01_Rows['p_name']; ?>" title="<?php echo $pList01_Rows['p_name']; ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo $pList01_Rows['p_name']; ?></h5>
                <p class="card-text"><?php echo mb_substr($pList01_Rows['p_intro'], 0, 30, "utf-8"); ?></p>
                <p>NT<?php echo $pList01_Rows['p_price']; ?></p>
                <a href="#" class="btn btn-primary">更多資訊</a>
                <a href="#" class="btn btn-success">放購物車</a>
            </div>
        </div>
        <?php if ($i % 4 == 0 || $i == $pList01->rowCount()) { ?>
        </div><?php } ?>
<?php $i++; } ?>
<div class="row mt-2">
    <?php //取得目前頁數
    if (isset($_GET['totalRows_rs'])) {
        $totalRows_rs = $_GET['totalRows_rs'];
    } else {
        $all_rs = $link->query($queryFirst);
        $totalRows_rs = $all_rs->rowCount();
    }

    $totalPages_rs = ceil($totalRows_rs / $maxRows_rs) - 1;
    $prev_rs = "&laquo;";
    $next_rs = "&raquo;";
    $separator = "|";
    $max_links = 20;
    $pages_rs = buildNavigation($pageNum_rs, $totalPages_rs, $prev_rs, $next_rs, $separator, $max_links, true, 3, "rs");
    ?>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <?php echo $pages_rs[0] . $pages_rs[1] . $pages_rs[2]; ?>
        </ul>
    </nav>
</div>
<?php } else { ?>
    <div class="alert alert-danger" role="alert">
 沒有啦!
</div>
<?php } ?>