<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title='管理员登录';
$this->params['breadcrumb'][]=$this->title;
?>
<div class="site-login">
    <h1 class="bg-success">用户登录</h1>
    <p class="bg-danger">请填写下面各项登录信息</p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form=ActiveForm::begin(['id'=>'login-form']);?>
                <?=$form->field($model,'username')->textInput(['focus'=>true]);?>
                <?=$form->field($model,'password')->passwordInput();?>
            <?=$form->field($model,'rememberMe')->checkbox()?>
            <div class="form-group">
                <?=Html::submitButton('登录',['class'=>'btn btn-primary','name'=>'login-button'])?>
            </div>
            <?php ActiveForm::end();?>
        </div>
    </div>
</div>
