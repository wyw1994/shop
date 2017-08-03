<?php
    $this->registerCssFile('@web/css/style/cart.css');
    $this->registerJsFile('@web/js/cart1.js',['depends'=>\yii\web\JqueryAsset::className()]);
?>
	<!-- 主体部分 start -->
	<div class="mycart w990 mt10 bc">
		<h2><span>我的购物车</span></h2>
		<table>
			<thead>
				<tr>
					<th class="col1">商品名称</th>
					<th class="col3">单价</th>
					<th class="col4">数量</th>	
					<th class="col5">小计</th>
					<th class="col6">操作</th>
				</tr>
			</thead>
			<tbody>
            <?php foreach($models as $model):?>
				<tr data-goods_id="<?=$model['id']?>">
					<td class="col1"><a href=""><?=\yii\helpers\Html::img('http://admin.yiishop.com/'.$model['logo'])?></a>  <strong><a href=""><?=$model['name']?></a></strong></td>
					<td class="col3">￥<span class="shop_price"><?=$model['shop_price']?></span></td>
					<td class="col4"> 
						<a href="javascript:;" class="reduce_num"></a>
						<input type="text" name="amount" value="<?=$model['amount']?>" class="amount"/>
						<a href="javascript:;" class="add_num"></a>
					</td>
					<td class="col5">￥<span><?=($model['shop_price']*$model['amount'])?></span></td>
					<td class="col6"><a href="javascript:;" class="del_goods">删除</a></td>
				</tr>
            <? endforeach;?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6">购物金额总计： <strong>￥ <span id="total"></span></strong></td>
				</tr>
			</tfoot>
		</table>
		<div class="cart_btn w990 bc mt10">
			<a href="" class="continue">继续购物</a>
			<a href="" class="checkout">结 算</a>
		</div>
	</div>
	<!-- 主体部分 end -->
<?php
$url=\yii\helpers\Url::to(['member/update-cart']);
$token=Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
              //监听+ - 按钮的点击事件
        $('.reduce_num,.add_num').click(function(){
            var goods_id=$(this).closest('tr').attr('data-goods_id');
            var amount=$(this).parent().find('.amount').val();
            //发送ajax post请求到member/update-cart{goods_id,amount}
            $.post("$url",{
                goods_id:goods_id,
                amount:amount,
                "_csrf-frontend":"$token"
            });
        });
        //删除按钮
        $(".del_goods").click(function(){
            if(confirm('是否删除该商品')){
               var goods_id=$(this).closest('tr').attr('data-goods_id');
               //发送ajax 的post请求到member/update-cart,{goods_id,amount}
               $.post("url",{
                   goods_id:goods_id,
                   amount:0,
                   '_csrf_frontend':"$token"
               });
               //删除当前商品的标签
               $(this).closest('tr').remove();
            }
        });
        function getTotal(){
            $total=0;
            $('#total').closest('tbody').find('tr').each(function(i,ele) {
                $shop_price=$(ele).find('.shop_price');
                $amount=$(ele).find('.amount').
            })
        }
JS
));