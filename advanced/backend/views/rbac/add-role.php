<style>
  #roleform-permissions{
      position:relative;
  }
#operation{
    position: absolute;
    height: 30px;
    top: 235px;
    left: 148px;
}
</style>
<script>
    window.onload=function(){
    var firstCheckBox=document.getElementById('roleform-permissions');
        var checkBoxChildren=document.getElementsByName('RoleForm[permissions][]');
        var selectToggle=document.getElementById('selectAll');
        var text=document.getElementById('text');
        selectToggle.onclick=function(){
            if(this.checked){
                for(var i=0;i<checkBoxChildren.length;i++){
                    checkBoxChildren[i].checked=true;
                    text.innerHTML='取消全选';
                }
            }else{
                for(var i=0;i<checkBoxChildren.length;i++){
                    checkBoxChildren[i].checked=!checkBoxChildren[i].checked;
                    text.innerHTML='全选';
                }
            }
        }
    }
</script>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description')->textarea();
//角色的权限
echo "<div id='operation'>".\yii\bootstrap\Html::checkbox(null,false,['class'=>'btn btn-info btn-md','id'=>'selectAll'])."<span id='text' class='bg-danger' style='display:inline-block;line-height:30px;margin-left:4px;border-radius:50%;color:red'>全选</span></div>";
echo $form->field($model,'permissions')->checkboxList(\backend\models\RoleForm::getPermissionOptions(),['class'=>'']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();