<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label')->label('菜单项名');
echo $form->field($model,'url')->label('路径');
echo $form->field($model,'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\Menu::getParentOptions(),'id','label'));
echo $form->field($model,'sort')->label('排序');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();