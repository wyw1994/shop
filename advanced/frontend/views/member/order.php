<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="<?=\yii\helpers\Url::to(['member/index'])?>"><img src="/images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr">
            <ul>
                <li style="background:#E3E3E3">1.我的购物车</li>
                <li class="cur" style="background:red">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->
    <div style="clear:both;"></div>
	<!-- 主体部分 start -->
<form method="post" action="<?=\yii\helpers\Url::to(['member/order'])?>">
	<div class="fillin w990 bc mt15">
		<div class="fillin_hd">
			<h2>填写并核对订单信息</h2>
		</div>

		<div class="fillin_bd">
			<!-- 收货人信息  start-->
			<div class="address">
				<h3>收货人信息</h3>
				<div class="address_info">
                    <?php foreach($address as $adr):?>
                        <p>
                            <input type="radio" value="<?=$adr['id']?>>" name="address_id" <?=$adr['is_default']?"checked":""?>/><?php echo $adr['name']." ".$adr['tel']." ".$adr['province']." ".$adr['city']." ".$adr['county']." ".$adr['detail']?>
                        </p>
<!--				<p>-->
<!--					<input type="radio" value="" name="address_id"/>张三  17002810530  北京市 昌平区 一号楼大街-->
<!--                </p>-->
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
						<tbody id="delivery_way">
                        <?php
                        $deliveries=\frontend\models\Order::$deliveries;
                        foreach($deliveries as $k=>$delivery):
                            echo ($k==1)?'<tr class="cur">':'<tr>';
                            ?>
								<td>
                                    <input type="radio" name="delivery_id" value="<?=$k?>" <?=$k==1?"checked":""?> /><?=$delivery['name']?>
								</td>
								<td>￥<span class="delivery_price"><?=$delivery['price']?></span></td>
								<td><?=$delivery['detail']?></td>
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
                        <?php
                        $payments=\frontend\models\Order::$payments;
                        foreach($payments as $k=>$payment):
                            echo ($k==2)?'<tr class="cur">':'<tr>';
                            ?>
                            <td class="col1">
                                <input type="radio" name="payment_id" value="<?=$k?>" <?=$k==2?"checked":""?> /><?=$payment['payment_name']?>
                            </td>
                            <td class="col2"><?=$payment['payment_detail']?></td>
                            </tr>
                        <?php endforeach;?>
					</table>
				</div>
			</div>
			<!-- 支付方式  end-->

<!--			<!-- 发票信息 start-->-->
<!--			<div class="receipt none">-->
<!--				<h3>发票信息 </h3>-->
<!---->
<!---->
<!--				<div class="receipt_select ">-->
<!--						<ul>-->
<!--							<li>-->
<!--								<label for="">发票抬头：</label>-->
<!--								<input type="radio" name="type" checked="checked" class="personal" />个人-->
<!--								<input type="radio" name="type" class="company"/>单位-->
<!--								<input type="text" class="txt company_input" disabled="disabled" />-->
<!--							</li>-->
<!--							<li>-->
<!--								<label for="">发票内容：</label>-->
<!--								<input type="radio" name="content" checked="checked" />明细-->
<!--								<input type="radio" name="content" />办公用品-->
<!--								<input type="radio" name="content" />体育休闲-->
<!--								<input type="radio" name="content" />耗材-->
<!--							</li>-->
<!--						</ul>-->
<!--				</div>-->
<!--			</div>-->
<!--			<!-- 发票信息 end-->-->

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
					<tbody id="orderInfo">
                        <?php foreach($goodsInfos as $goods):?>
						<tr>
							<td class="col1"><a href=""><img src="http://admin.yiishop.com/<?=$goods['logo']?>" alt="" /></a>  <strong><a href=""><?=$goods['intro']?></a></strong></td>
					 		<td class="col3" style="color:red;font-size:14px;">￥<span style="color:red;font-size:14px;"><?=$goods['shop_price']?></span></td>
							<td class="col4 amount"><?=$goods['amount']?></td>
							<td class="col5" style="color:red;font-size:14px;">￥<span style="color:red;font-size:14px;"></span></td>
						</tr>
                        <?php endforeach;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5">
								<ul>
									<li>
										<span>商品总金额：</span>
										<em style="color:red">￥</em><em style="color:red;font-size:18px;" id="total"></em>
									</li>
									<li>
										<span>&nbsp;&nbsp;运费：</span>
                                        <em style="color:red">￥</em><em id='delivery' style="color:red;font-size:18px;" ></em>
									</li>
									<li>
										<span>应付总额：</span>
                                        <em style="color:red">￥</em><em style="color:red;"  id="totalPrice"></em>
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
            <input type="hidden" name="_csrf-frontend" id='csrf' value="<?= Yii::$app->request->csrfToken ?>">
            <input type="submit">
			<p>应付总额：<strong></strong></p>
		</div
	</div>
</form>
	<!-- 主体部分 end -->
<?php
$url = \yii\helpers\Url::to(['member/update-cart']);
$token = Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
        //点击提交
           //监听+ - 按钮的点击事件
        $(".reduce_num,.add_num").click(function(){
            //console.log($(this));
            var goods_id = $(this).closest('tr').attr('data-goods_id');
            var amount = $(this).parent().find('.amount').val();
            //发送ajax post请求到site/update-cart  {goods_id,amount}
            $.post("$url",{goods_id:goods_id,amount:amount,"_csrf-frontend":"$token"});
        });
        //删除按钮
        $(".del_goods").click(function(){
            if(confirm('是否删除该商品')){
                var goods_id = $(this).closest('tr').attr('data-goods_id');
                //发送ajax post请求到site/update-cart  {goods_id,amount}
                $.post("$url",{goods_id:goods_id,amount:0,"_csrf-frontend":"$token"});
                //删除当前商品的标签
                $(this).closest('tr').remove();
            }
        });
JS

));