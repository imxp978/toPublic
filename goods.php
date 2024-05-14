<!-- 資料庫連線程式載入 -->
<?php require_once('Connections/conn_db.php'); ?>
<!-- 如果SESSION沒有啟動，則啟動SESSION功能，這是跨網頁變數存取 -->
<?php (!isset($_SESSION)) ? session_start() : ""; ?>
<!-- 載入PHP函數庫 -->
<?php require_once("php_lib.php"); ?>
<!doctype html>
<html lang="zh-TW">

<head>
    <?php require_once('headfile.php'); ?>
    <link rel="stylesheet" href="fancybox-2.1.7/source/jquery.fancybox.css">
</head>

<body>
    <section id="header">
        <?php require_once('navbar.php'); ?>
    </section>
    <section id="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    <!-- 引入sidebar module -->
                    <?php require_once('sidebar.php'); ?>
                    <!-- 引入熱銷商品module -->
                    <?php require_once('hot.php'); ?>
                </div>

                <div class="col-md-10">

                    <!-- 引入breadCrumb模組 -->
                    <?php require_once('breadcrumb.php') ?>
                    <!-- 引入商品列表模組 -->
                    <?php //require_once('goods_content.php') 
                    ?>
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-3">
                                <?php 
                                    $SQLstring = sprintf("SELECT * FROM product_img 
                                    WHERE product_img.p_id=%d
                                    ORDER BY sort", $_GET['p_id']);
                                    $img_rs = $link->query($SQLstring);
                                    $imgList = $img_rs->fetch();
                                ?>
                                <img id="showGoods" name="showGoods" 
                                src="product_img/<?php echo $imgList['img_file']; ?>" 
                                alt="<?php echo $data['p_name']; ?>"
                                title= "<?php echo $data['p_name']; ?>"
                                class="img-fluid">

                                <div class="row mt-2">
                                    <?php do { ?>
                                        <div class="col-md-4">
                                            <a href="product_img/<?php echo $imgList['img_file'] ?>"
                                                rel="group" class="fancybox" 
                                                title="<?php echo $data['p_name']; ?>">
                                                <img src="product_img/<?php echo $imgList['img_file'] ?>"
                                                alt = "<?php echo $data['p_name']; ?>" 
                                                title ="<?php echo $data['p_name']; ?>"
                                                class="img-fluid">
                                            </a>
                                        </div>
                                    <?php }  while ($imgList = $img_rs->fetch()); ?>
                                </div>
                            </div>


                        <!-- <div class="col-md-3">
                                <img id="showGoods" name="showGoods" src="product_img/zoom2555551.webp" class="img-fluid rounded-start" alt="Biore 蜜妮">
                                <div class="row mt-2">
                                    
                                    <div class="col-md-4">
                                        <a href="product_img/zoom2555551.webp"><img src="product_img/zoom2555551.webp" alt="蜜妮" class="img-fluid"></a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="product_img/zoom2555552.webp"><img src="product_img/zoom2555552.webp" alt="蜜妮" class="img-fluid"></a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="product_img/zoom2555553.webp"><img src="product_img/zoom2555553.webp" alt="蜜妮" class="img-fluid"></a>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $data['p_name']; ?></h5>
                                    <p class="card-text"><?php echo $data['p_intro']; ?></p>
                                    <h4 class="color_e600a0">$<?php echo $data['p_price']; ?></h4>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text color-success" id="inputGroup-sizing-lg">數量</span>
                                                <input type="number" id="qty" name="qty" value="1" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg">
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <button name="button01" id="button01" type="button" class="btn btn-success btn-lg color-success" 
                                            onclick = "addcart(<?php echo $data['p_id']; ?>)">
                                                加入購物車
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $data['p_content']; ?>
                    <!-- <?php require_once('drop-box.php')?> -->

                </div>
            </div>

        </div>
    </section>
    <section id="scontent">
        <?php require_once('scontent.php'); ?>
    </section>
    <section id="footer">
        <?php require_once('footer.php'); ?>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

</html>

<?php
function activeShow($num, $chkPoint)
{
    return (($num == $chkPoint) ? 'active' : '');
}
?>

<?php require_once ('jsfile.php'); ?>
<script type="text/javascript" src="fancybox-2.1.7/source/jquery.fancybox.js"></script>
<script type="text/javascript">
    $(function(){
        //定義在滑鼠滑過圖片檔明田入主圖src中
        $(".card .row.mt-2 .col-md-4 a").mouseover(function(){
            var imgsrc=$(this).children("img").attr("src");
            $("#showGoods").attr({"src":imgsrc});
        }); 
        $(".fancybox").fancybox();
    });
</script>
<script>
    function addcart(p_id) {
        let qty = $("#qty").val();
        if(qty<=0) {
            alert("數量不能為零或負數 懂嗎?");
            return(false);
        } 
        if(qty==undefined) {
            qty=1;
        } else if (qty >= 50) {
            alert("數量限制50內");
            return(false);
        }
        // 利用jquery $.ajax函數呼叫後台的addcart.php

        $.ajax({
            url: 'addcart.php',
            type:'get',
            dataType: 'json',
            data: {p_id: p_id, qty: qty,},
            success: function (data) {
                if (data.c == true) {
                    alert(data.m);
                }
            }, 
            error: function (data) {
                alert('後臺壞了')
            }
        });
    }
</script>