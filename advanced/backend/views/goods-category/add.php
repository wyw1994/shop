<?php
/**
 * @var $this \yii\web\View
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput(['class'=>'bg-success','style'=>'display:block']);
echo $form->field($model,'parent_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree bg-success"></ul>';
echo $form->field($model,'intro')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
//<link rel="stylesheet" href="/ztree/css/demo.css" type="text/css">
//    <link rel="stylesheet" href="/ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">
//    <script type="text/javascript" src="/ztree/js/jquery-1.4.4.min.js"></script>
//    <script type="text/javascript" src="/ztree/js/jquery.ztree.core.js"></script>
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes=\yii\helpers\Json::encode($categories);
$js=new \yii\web\JsExpression(
    <<<JS
    var zTreeObj;
    var setting={
        data:{
            simpleData:{
                enable:true,
                idKey:'id',
                pIdKey:'parent_id',
                rootPId:0,
                keep: {
                    parent: true,
                }
            }
        },
        callback:{
            onClick:function(event,treeId,treeNode){
                 $("#goodscategory-parent_id").val(treeNode.id);
            }
        }
    };
    var zNodes={$zNodes};
    zTreeObj=$.fn.zTree.init($("#treeDemo"),setting,zNodes);
    zTreeObj.expandAll(true);
    var node=zTreeObj.getNodeByParam('id',$("#goodscategory-parent_id").val(),null);
   zTreeObj.selectNode(node);
JS
);
$this->registerJs($js);