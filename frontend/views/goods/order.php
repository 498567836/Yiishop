<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>填写核对订单信息</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/fillin.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">

    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/cart2.js"></script>

</head>
<body>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>您好，欢迎来到京西！[<a href="login.html">登录</a>] [<a href="register.html">免费注册</a>] </li>
                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<form action="/goods/order" method="post" id="submit">
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php $n=0;foreach ($address as $address2):$n++?>
                <p>
                    <input type="radio" <?=($n==1)?'checked':''?> value="<?=$address2->id?>" name="address_id"/><?=$address2->username.' '.$address2->tel.' '.$address2->province.' '.$address2->city.' '.$address2->zone.' '.$address2->address?>
                </p>
                <?php endforeach;?>
            </div>
        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>
            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $n=0; foreach (\frontend\models\Order::$delivery as $delivery2):$n++?>
                    <tr class="<?=($n==1)?'cur':''?>">
                        <td price="<?=number_format($delivery2['price'],2)?>">
                            <input type="radio" class="delivery_"  name="delivery" <?=($n==1)?'checked':''?> value="<?=$n?>" /><?=$delivery2['name']?>
                        </td>
                        <td>￥<?=number_format($delivery2['price'],2)?></td>
                        <td><?=$delivery2['intro']?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>
            <div class="pay_select">
                <table>
                    <?php $n=0; foreach (\frontend\models\Order::$pay as $pay2):$n++?>
                    <tr class="<?=($n==1)?'cur':''?>">
                        <td class="col1"><input type="radio" <?=($n==1)?'checked':''?> name="pay"  value="<?=$n?>" /><?=$pay2['name']?></td>
                        <td class="col2"><?=$pay2['intro']?></td>
                    </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </div>
        <!-- 支付方式  end-->
        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>
            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php $total=0;foreach ($goods as $goods2):$total+=$goods2->shop_price*$carts[$goods2->id];?>
                <tr>
                    <td class="col1"><a href=""><img src="<?=$goods2->logo?>" alt="" /></a>  <strong><a href=""><?=$goods2->name?></a></strong></td>
                    <td class="col3">￥<?=$goods2->shop_price?></td>
                    <td class="col4"> <?=$carts[$goods2->id]?></td>
                    <td class="col5"><span>￥<?=number_format($goods2->shop_price*$carts[$goods2->id],2)?></span></td>
                </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=count($goods)?> 件商品，总商品金额：</span>
                                <em>￥<?=number_format($total,2)?></em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>-￥0.00</em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em id="delivery_2">￥<?=number_format(\frontend\models\Order::$delivery[1]['price'],2)?></em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em  id="total0">￥<span id="total1"><?=number_format($total,2)?></span></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
<!--        <input  type="submit"  style="display:none" id="submit_btn" value="提交订单">-->
        <a href="javascript:void(0)" onclick="document.getElementById('submit').submit();return false"><span>提交订单</span></a>
        <p>应付总额：<strong id="total2"></strong><strong>元</strong></p>

    </div>
</div>
</form>
<!-- 主体部分 end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="/images/xin.png" alt="" /></a>
        <a href=""><img src="/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="/images/police.jpg" alt="" /></a>
        <a href=""><img src="/images/beian.gif" alt="" /></a>
    </p>
</div>
<!-- 底部版权 end -->
<script type="text/javascript">
    $(function () {
        var price= $('#total1').text().replace(',', "");
        var price1=eval(parseFloat(price)+parseFloat($('.delivery_select').find('td:first').attr('price').replace(',', ""))).toFixed(2);
        $('#total1').text(price1);
        console.debug(price1);
        total();
    });
    var price=$('#total1').text().replace(',', "");
    $('.delivery_').click( function () {
        var delivery =eval(parseFloat(price)+parseFloat($(this).closest('td').attr('price').replace(',', ""))).toFixed(2);
        $('#delivery_2').text('￥'+$(this).closest('td').attr('price'));
        $('#total1').text(delivery);
        //console.debug($(this).closest('td').attr('price'));
        //console.debug($('#total1').text());
        //console.debug(delivery);
        total();
    } );
    function total() {
        $('#total2').text($('#total0').text());
    }

</script>
</body>
</html>
