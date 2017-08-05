<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->label('用户名');
echo $form->field($model,'password')->passwordInput()->label('密码');
echo $form->field($model,'email')->label('电子邮箱');
//echo $form->field($model,'roles')->radioList(\yii\helpers\ArrayHelper::map(\yii::$app->authManager->getRoles(),'name','description'),['class'=>'bg-info']);
echo $form->field($model,'roles')->checkboxList(\yii\helpers\ArrayHelper::map(\yii::$app->authManager->getRoles(),'name','description'),['class'=>'bg-info']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();
