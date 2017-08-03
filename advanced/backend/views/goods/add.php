<?php
if(isset($editId)){
    $url=['goods/add','editId'=>$editId];
}else{
    $url=['goods/add'];
}
$form=\yii\bootstrap\ActiveForm::begin([
        'action'=>$url,
        'method'=>'post'
    ]
);
echo $form->field($model,'name');
echo $form->field($model,'logo_file')->fileInput();
echo $form->field($model,'logo')->hiddenInput()->label(false);
echo $form->field($model,'goods_category_id')->hiddenInput();
$zTree=\backend\widgets\ZTreeWidget::widget([
        'setting'=>'{
                data:{
                    simpleData:{
                        enable:true,
                        pIdKey:"parent_id",
                    }
                },
                callback:{
                    onClick:function(event,treeId,treeNode){
                        $("#goods-goods_category_id").val(treeNode.id);
                    }
                }
        }',
            'zNodes'=>\backend\models\GoodsCategory::getZNodes(),
            'selectNodes'=>['id'=>$model->goods_category_id],
    ]);
    echo $zTree;
    echo $form->field($model,'brand_id')->dropDownList(\backend\models\Goods::getBrandOptions(),['prompt'=>'=请选择品牌=']);
    echo $form->field($model,'market_price');
    echo $form->field($model,'shop_price');
    echo $form->field($model,'stock');
    echo $form->field($model,'is_on_sale',['inline'=>true])->radioList(\backend\models\Goods::$sale_options);
    echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Goods::$status_options);
    echo $form->field($model,'sort');
    echo $form->field($introModel,'content')->widget('kucha\ueditor\UEditor',[
        'clientOptions'=>[
            //设置区域大小
            'initialFrameHeight'=>'200',
            //设置语言
            'lang'=>'en',//中文为zh-cn,
            //定制菜单
            /*'toolbars' => [
          [
              'fullscreen', 'source', 'undo', 'redo', '|',
              'fontsize',
              'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
              'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
              'forecolor', 'backcolor', '|',
              'lineheight', '|',
              'indent', '|'
          ],
      ]*/

        ],
    ]);
    echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
    \yii\bootstrap\ActiveForm::end();