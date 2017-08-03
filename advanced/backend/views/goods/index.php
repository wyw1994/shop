<style>
    input[type='text']{
        height:30px;
        line-height:30px;
        text-indent: 3px;
        letter-spacing: 2px;
        margin-right: 5px;
    }
    .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 0;
        line-height: 1.42857143;
        vertical-align: middle;
        border-top: 1px solid #ddd;
        text-align: center;
    }
    th{
        text-align: center;
        vertical-align: middle;
        background:paleturquoise;
    }
    a{
        margin-top:-10px;
    }
    .pagination > li > a, .pagination > li > span{
        display: inline-block;
    }
</style>
<h1  style="font-size:30px;letter-spacing: 2px;color:lightblue;margin-bottom: 10px;">商品列表&nbsp;&nbsp;<?=\yii\bootstrap\Html::a('添加商品',\yii\helpers\Url::to(['goods/add']),['class'=>'btn btn-info','style'=>'margin-top:4px'])?></h1>
<div class="bg-info" style="padding:15px 0 8px 0;margin-bottom: 10px;">
<?php
$form=\yii\bootstrap\ActiveForm::begin(
        [
            'method'=>'get',
            'action'=>\yii\helpers\Url::to(['goods/index']),
            'options'=>[
                'class'=>'form-inline'
            ]
        ]
);
echo $form->field($model,'name')->textInput(['placeholder'=>'商品名','class'=>'input-medium search-query','name'=>'keyword'])->label(false);
echo $form->field($model,'sn')->textInput(['placeholder'=>'商品编号','class'=>'input-medium search-query'])->label(false);
echo $form->field($model,'minPrice')->textInput(['placeholder'=>'价格下限','class'=>'input-medium search-query'])->label(false);
echo $form->field($model,'maxPrice')->textInput(['placeholder'=>'价格上限','class'=>'input-medium search-query'])->label('-');
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-default','style'=>'margin-top:-10px']);
\yii\bootstrap\ActiveForm::end();
//    echo \yii\bootstrap\Html::beginForm(\yii\helpers\Url::to(['goods/index?']),'get',['name'=>'search_form']);
//    echo \yii\bootstrap\Html::textInput('name',null,['placeholder'=>'商品名']);
//    echo \yii\bootstrap\Html::textInput('sn',null,['placeholder'=>'商品编号']);
//    echo \yii\bootstrap\Html::textInput('minPrice',null,['placeholder'=>'价格下限']);
//    echo \yii\bootstrap\Html::textInput('maxPrice',null,['placeholder'=>'价格上限']);
//    echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-warning']);
//    echo \yii\bootstrap\Html::endForm();
?>
</div>
<table class="table  table-bordered table-responsive table-striped table-condensed table-hover">
    <th>商品ID</th>
    <th>商品名</th>
    <th>商品编号</th>
    <th>商品logo</th>
    <th>商品种类</th>
    <th>商品品牌</th>
    <th>市场价格</th>
    <th>本店价格</th>
    <th>库存量</th>
    <th>是否上架</th>
    <th>状 态</th>
    <th>排 序</th>
    <th>添加时间</th>
    <th style="min-width:90px;">操 作</th>
    <?php foreach($models as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td><?=$item->name?></td>
            <td><?=$item->sn?></td>
            <td><?=
                isset($item->logo)?(\yii\bootstrap\Html::img($item->logo,['style'=>'width:60px;height:60px;margin:10px 0'])):(\yii\bootstrap\Html::img('@webroot/upload/logo/defaultLogo.jpg',['style'=>'width:60px;height:60px;margin:10px 0']))?>
            </td>
            <td><?=\backend\models\Goods::getNameOfOneGoodsCategory($item->goods_category_id)->name;?></td>
            <td><?=\backend\models\Goods::getNameOfOneGoodsBrand($item->brand_id)->name;?></td>
            <td><?=$item->market_price?></td>
            <td><?=$item->shop_price?></td>
            <td><?=$item->stock?></td>
            <td><?=\backend\models\Goods::$sale_options[$item->is_on_sale]?></td>
            <td><?=\backend\models\Goods::$status_options[$item->status]?></td>
            <td><?=$item->sort?></td>
            <td><?=date('Y-m-d',$item->create_time)?></td>
            <td style="position: relative">
                <?=\yii\bootstrap\Html::a('<span class="glyphicon glyphicon-pencil"></span>',['goods/edit','id'=>$item->id],['class'=>'btn btn-info btn-xs','style'=>'position:absolute;left:8px;','title'=>'编辑'])?>
                <?=\yii\bootstrap\Html::a('<span class="glyphicon glyphicon-picture"></span>',['goods/gallery','id'=>$item->id],['class'=>'btn btn-success  btn-xs btn-xs','style'=>'position:absolute;left:43px;','title'=>'相册'])?>
                <?=\yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash"></span>',['goods/del','id'=>$item->id],['class'=>'btn btn-warning  btn-xs','style'=>'position:absolute;right:8px;','title'=>'删除','onclick'=>'return confirm("您确定要删除这条商品吗？")'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
])?>
