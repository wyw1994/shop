<style>

</style>
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Brand */
/* @var $form yii\widgets\ActiveForm */
use yii\web\JsExpression;
use xj\uploadify\Uploadify;
//外部TAG
echo Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' =>false,
    'jsOptions' => [
        'width' => 80,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
        function(file, errorCode, errorMsg, errorString) {
            console.log('The file ' + file.name + ' 不能被加载 ' + errorString + errorCode + errorMsg);
        }
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
        function(file, data, response){
            data = JSON.parse(data);
            if (data.error) {
                 $('#upImg').append('<span style="color:red;margin-left:10px;">'+data.msg+'</span>');
            } else {
                $('#upImg').attr('src',data.fileUrl).show();
                $('#inputImg').val(data.fileUrl);
               console.debug($('#inputImg'));
            }
        }
EOF
        ),
    ]
]);
?>
<div class="brand-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'intro')->textarea(['rows' => 6]) ?>
    <?php echo Html::tag('p','上传图片',['class'=>'bg-success'])?>
    <?=$form->field($model,'logo')->hiddenInput(['id'=>'inputImg']);?>
    <?php
        echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
        if($model->logo){
            echo Html::img(\yii::getAlias('@web').$model->logo,['style'=>'width:80px;height:80px']);
        }else{
            echo Html::img('',['style'=>'display:none;width:80px;height:80px','id'=>'upImg']);
        }
    ?>
    <?php echo '<br>';?>
    <?= $form->field($model, 'sort')->textInput([
            'class'=>'bg-danger',
            'id'=>'sortInput',
            'style'=>'margin-top:'
    ]) ?>

    <?= $form->field($model, 'status')->radioList([-1=>'删除',0=>'隐藏',1=>'正常']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
