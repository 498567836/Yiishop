<h1><?=$model->name?></h1>
<div id="myCarousel" class="carousel slide">
    <!-- 轮播（Carousel）指标 -->
    <ol class="carousel-indicators">
        <?php $n=0; foreach ($photos as $photo):?>
<!--        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>-->
        <li data-target="#myCarousel" data-slide-to="<?=$n++?>" ></li>
        <?php endforeach;?>
    </ol>
    <!-- 轮播（Carousel）项目 -->
    <div class="carousel-inner">
        <?php  $n=1; foreach ($photos as $photo):?>
            <div class="item <?=$n==1?'active':''?>">
                <img src="<?=$photo->path?>" >
                <div class="carousel-caption">图片<?=$n++?></div>
            </div>
        <?php endforeach;?>
    </div>
    <!-- 轮播（Carousel）导航 -->
    <a class="carousel-control left" href="#myCarousel"
       data-slide="prev">&lsaquo;
    </a>
    <a class="carousel-control right" href="#myCarousel"
       data-slide="next">&rsaquo;
    </a>
</div>
<!-- 控制按钮 -->

<div style="text-align:center;">
    <input type="button" class="btn start-slide" value="Start">
    <input type="button" class="btn pause-slide" value="Pause">
    <input type="button" class="btn prev-slide" value="Previous Slide">
    <input type="button" class="btn next-slide" value="Next Slide">
    <input type="button" class="btn slide-one" value="Slide 1">
    <input type="button" class="btn slide-two" value="Slide 2">
    <input type="button" class="btn slide-three" value="Slide 3">
</div>
<script>
    $(function(){
        // 初始化轮播
        $(".start-slide").click(function(){
            $("#myCarousel").carousel('cycle');
        });
        // 停止轮播
        $(".pause-slide").click(function(){
            $("#myCarousel").carousel('pause');
        });
        // 循环轮播到上一个项目
        $(".prev-slide").click(function(){
            $("#myCarousel").carousel('prev');
        });
        // 循环轮播到下一个项目
        $(".next-slide").click(function(){
            $("#myCarousel").carousel('next');
        });
        // 循环轮播到某个特定的帧
        $(".slide-one").click(function(){
            $("#myCarousel").carousel(0);
        });
        $(".slide-two").click(function(){
            $("#myCarousel").carousel(1);
        });
        $(".slide-three").click(function(){
            $("#myCarousel").carousel(2);
        });
    });
</script>
<?=$goodsintro->content?>