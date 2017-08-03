	<!-- 列表主体 start -->
	<div class="list w1210 bc mt10">
		<!-- 面包屑导航 start -->
		<div class="breadcrumb">
            <?php
                $indexUrl=\yii\helpers\Url::to(['member/index']);
                $tops="<h2>当前位置：<a href='$indexUrl'>首页</a>";
                foreach($menu as $m){
                    $url=\yii\helpers\Url::to(['member/list','cate_id'=>$m['id']]);
                    $tops.="><a href='$url'>$m[name]</a>";
                }
                $tops.='</h2>';
                echo $tops;
            ?>
		</div>
		<!-- 面包屑导航 end -->

		<!-- 左侧内容 start -->
		<div class="list_left fl mt10">
			<!-- 分类列表 start -->
			<div class="catlist">
				<h2> <?
                        foreach ($catesGroupByDepth as $val){
                            echo $val[0];
                        }
                    ?>
                </h2>
				<div class="catlist_wrap">
                  <?php if(!empty($catesGroupByDepth[1])):?>
                    <?php foreach ($catesGroupByDepth[1] as $id=>$name):?>
					<div class="child">
						<h3 class="on"><b></b><?=$name?></h3>
						<ul>
                            <?php foreach ($catesGroupByPid as $pid=>$childArray):?>
                                <?php if($id==$pid):?>
                                    <?php foreach ($childArray as $k=>$v):?>
							            <li><a href="<?=\yii\helpers\Url::to(['member/list','cate_id'=>$k])?>"><?=$v?></a></li>
                                    <?php endforeach;?>
                                <?php endif;?>
                            <?php endforeach;?>
                        </ul>
					</div>
                    <?php endforeach;?>
                  <?php endif;?>
				</div>

				<div style="clear:both; height:1px;"></div>
			</div>
			<!-- 分类列表 end -->

			<div style="clear:both;"></div>

			<!-- 新品推荐 start -->
			<div class="newgoods leftbar mt10">
				<h2><strong>新品推荐</strong></h2>
				<div class="leftbar_wrap">
					<ul>
                        <?php
                        if(!empty($goodsOfNew)){
                            shuffle($goodsOfNew);
                            $count=count($goodsOfNew);
                            $count=$count>3?3:$count;
                            $goodsOfNew=array_slice($goodsOfNew,0,$count);
                        }
                        ?>
                        <?php if(!empty($goodsOfNew)){foreach( $goodsOfNew as $goodNew):?>
						<li>
							<dl>
								<dt><a href="<?=\yii\helpers\Url::to(['member/goods-detail','id'=>$goodNew['id']])?>"><img src="http://admin.yiishop.com/<?=$goodNew['logo']?>" alt="" /></a></dt>
								<dd><a href=""><?=$goodNew['name']?></a></dd>
								<dd><strong><?=$goodNew['shop_price']?></strong></dd>
							</dl>
						</li>
                        <?php endforeach;}?>

<!--						<li class="last">-->
<!--							<dl>-->
<!--								<dt><a href=""><img src="/images/list_hot3.jpg" alt="" /></a></dt>-->
<!--								<dd><a href="">皮尔卡丹pierrecardin 男士长...</a></dd>-->
<!--								<dd><strong>￥1240.50</strong></dd>-->
<!--							</dl>-->
<!--						</li>-->
					</ul>
				</div>
			</div>
			<!-- 新品推荐 end -->

			<!--热销排行 start -->
			<div class="hotgoods leftbar mt10">
				<h2><strong>热销排行榜</strong></h2>
				<div class="leftbar_wrap">
					<ul>
						<li></li>
					</ul>
				</div>
			</div>
			<!--热销排行 end -->

			<!-- 最近浏览 start -->
			<div class="viewd leftbar mt10">
				<h2><a href="">清空</a><strong>最近浏览过的商品</strong></h2>
				<div class="leftbar_wrap">
					<dl>
						<dt><a href=""><img src="/images/hpG4.jpg" alt="" /></a></dt>
						<dd><a href="">惠普G4-1332TX 14英寸笔记...</a></dd>
					</dl>

					<dl class="last">
						<dt><a href=""><img src="/images/crazy4.jpg" alt="" /></a></dt>
						<dd><a href="">直降200元！TCL正1.5匹空调</a></dd>
					</dl>
				</div>
			</div>
			<!-- 最近浏览 end -->
		</div>
		<!-- 左侧内容 end -->

		<!-- 列表内容 start -->
		<div class="list_bd fl ml10 mt10">
			<!-- 热卖、促销 start -->
			<div class="list_top">
				<!-- 热卖推荐 start -->
				<div class="hotsale fl">
					<h2><strong><span class="none">热卖推荐</span></strong></h2>
					<ul>
                        <?php
                        if(!empty($goodsOfHot)){
                            shuffle($goodsOfHot);
                            $count=count($goodsOfHot);
                            $count=$count>3?3:$count;
                            $goodsOfHot=array_slice($goodsOfHot,0,$count);
                        }
                        ?>
                        <?php if(!empty($goodsOfHot)){foreach($goodsOfHot as $goodHot):?>
                            <li>
                                <dl>
                                    <dt><a href="<?=\yii\helpers\Url::to(['member/goods-detail','id'=>$goodHot['id']])?>"><img src="http://admin.yiishop.com/<?=$goodHot['logo']?>" alt="" /></a></dt>
                                    <dd class="name"><a href=""><?=$goodHot['name']?></a></dd>
                                    <dd class="price"><strong><?=$goodHot['shop_price']?></strong></dd>
                                    <dd class="buy"><a href="<?=\yii\helpers\Url::to(['member/goods-detail','id'=>$goodHot['id']])?>"><span>立即抢购</span></a></dd>
                                </dl>
                            </li>
                        <?php endforeach;}?>

<!--						<li>-->
<!--							<dl>-->
<!--								<dt><a href=""><img src="/images/hpG4.jpg" alt="" /></a></dt>-->
<!--								<dd class="name"><a href="">惠普G4-1332TX 14英寸笔记本电脑 （i5-2450M 2G 5</a></dd>-->
<!--								<dd class="price">特价：<strong>￥2999.00</strong></dd>-->
<!--								<dd class="buy"><span>立即抢购</span></dd>-->
<!--							</dl>-->
<!--						</li>-->
					</ul>
				</div>
				<!-- 热卖推荐 end -->

				<!-- 促销活动 start -->
				<div class="promote fl">
					<h2><strong><span class="none">促销活动</span></strong></h2>
					<ul>
						<li><b>.</b><a href="">DIY装机之向雷锋同志学习！</a></li>
						<li><b>.</b><a href="">京东宏碁联合促销送好礼！</a></li>
						<li><b>.</b><a href="">台式机笔记本三月巨惠！</a></li>
						<li><b>.</b><a href="">富勒A53g智能人手识别鼠标</a></li>
						<li><b>.</b><a href="">希捷硬盘白色情人节专场</a></li>
					</ul>

				</div>
				<!-- 促销活动 end -->
			</div>
			<!-- 热卖、促销 end -->

			<div style="clear:both;"></div>

			<!-- 商品筛选 start -->
			<div class="filter mt10">
				<h2><a href="">重置筛选条件</a> <strong>商品筛选</strong></h2>
				<div class="filter_wrap">
					<dl>
						<dt>品牌：</dt>
						<dd class="cur"><a href="">不限</a></dd>
                        <?php foreach($brands as $brand):?>
                            <dd><a href="<?=\yii\helpers\Url::to(['member/list','brand_id'=>$brand['id']])?>"><?=$brand['name']?></a></dd>
                        <?php endforeach;?>
<!--						<dd><a href="">联想（Lenovo）</a></dd>-->
					</dl>

					<dl>
						<dt>价格：</dt>
						<dd class="cur"><a href="">不限</a></dd>
						<dd><a href="">1000-1999</a></dd>
						<dd><a href="">2000-2999</a></dd>
						<dd><a href="">3000-3499</a></dd>
						<dd><a href="">3500-3999</a></dd>
						<dd><a href="">4000-4499</a></dd>
						<dd><a href="">4500-4999</a></dd>
						<dd><a href="">5000-5999</a></dd>
						<dd><a href="">6000-6999</a></dd>
						<dd><a href="">7000-7999</a></dd>
					</dl>

					<dl>
						<dt>尺寸：</dt>
						<dd class="cur"><a href="">不限</a></dd>
						<dd><a href="">10.1英寸及以下</a></dd>
						<dd><a href="">11英寸</a></dd>
						<dd><a href="">12英寸</a></dd>
						<dd><a href="">13英寸</a></dd>
						<dd><a href="">14英寸</a></dd>
						<dd><a href="">15英寸</a></dd>
					</dl>

					<dl class="last">
						<dt>处理器：</dt>
						<dd class="cur"><a href="">不限</a></dd>
						<dd><a href="">intel i3</a></dd>
						<dd><a href="">intel i5</a></dd>
						<dd><a href="">intel i7</a></dd>
						<dd><a href="">AMD A6</a></dd>
						<dd><a href="">AMD A8</a></dd>
						<dd><a href="">AMD A10</a></dd>
						<dd><a href="">其它intel平台</a></dd>
					</dl>
				</div>
			</div>
			<!-- 商品筛选 end -->

			<div style="clear:both;"></div>
			<!-- 商品列表 start-->
                <table class="mt10 goodslist table">
                    <thead>
                    <tr>
                        <th>	<!-- 排序 start -->
                            <div class="sort mt10">
                                <dl>
                                    <dt>排序：</dt>
                                    <dd class="cur"><a href="">销量</a></dd>
                                    <dd><a href="">价格</a></dd>
                                    <dd><a href="">评论数</a></dd>
                                    <dd><a href="">上架时间</a></dd>
                                </dl>
                            </div>
                            <!-- 排序 end -->

                            <div style="clear:both;"></div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <?php if($get_brand_id==0):?>
                                <?php foreach($goodsByBrand as $goodByBrand):?>
                                    <?php foreach ($goodByBrand as $good):?>
                                <dl>
                                    <dt><a href="<?=\yii\helpers\Url::to(['member/goods-detail','id'=>$good['id']])?>"><img src="http://admin.yiishop.com/<?=$good['logo']?>" alt="" /></a></dt>
                                    <dd><a href=""><?=$good['name']?></a></dd>
                                    <dd><strong><?=$good['shop_price']?></strong></dd>
                                    <dd><a href=""><em>已有10人评价</em></a></dd>
                                </dl>
                                <?php endforeach;?>
                                <?php endforeach;?>
                            <?php endif;?>
                        </td>
                    </tr>
<!--					<li>-->
<!--						<dl>-->
<!--							<dt><a href=""><img src="/images/goods3.jpg" alt="" /></a></dt>-->
<!--							<dd><a href="">三星Galaxy Tab P6800 7.7英寸 3G手机版 蓝牙3.0 魔丽屏 金属银</a></dd>-->
<!--							<dd><strong>￥4699.00</strong></dd>-->
<!--							<dd><a href=""><em>已有34人评价</em></a></dd>-->
<!--						</dl>-->
<!--					</li>-->
                    </tbody>
                </table>
			<!-- 商品列表 end-->
                <?php
                $this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
                $this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
                $this->registerJs('$(".table").DataTable({
});');
                ?>
			<!-- 分页信息 start -->
<!--			<div class="page mt20">-->
<!--				<a href="">首页</a>-->
<!--				<a href="">上一页</a>-->
<!--				<a href="">1</a>-->
<!--				<a href="">2</a>-->
<!--				<a href="" class="cur">3</a>-->
<!--				<a href="">4</a>-->
<!--				<a href="">5</a>-->
<!--				<a href="">下一页</a>-->
<!--				<a href="">尾页</a>&nbsp;&nbsp;-->
<!--				<span>-->
<!--					<em>共8页&nbsp;&nbsp;到第 <input type="text" class="page_num" value="3"/> 页</em>-->
<!--					<a href="" class="skipsearch" href="javascript:;">确定</a>-->
<!--				</span>-->
			</div>
			<!-- 分页信息 end -->
		</div>
		<!-- 列表内容 end -->
	</div>
	<!-- 列表主体 end-->
    <div style="clear:both;"></div>