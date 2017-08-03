<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: middle;
        text-align: center;
        height:60px;
        overflow: hidden;
        border-top: 1px solid #ddd;
        font-family: 'Microsoft Yahei';
    }
    th{
        background:lightcyan;
    }
 .table > thead > tr > td:nth-child(3), .table > tbody > tr > td:nth-child(3), .table > tfoot > tr > td:nth-child(3){
        text-align: left;
    }
</style>
<h1 class="bg-info">角色列表</h1>
<table class="table table-responsive table-bordered table-striped table-condensed table-hover">
    <thead>
    <tr>
        <th style="min-width:100px">名称</th>
        <th style="min-width:150px">描述</th>
        <th>权限</th>
        <th style="min-width: 100px;">操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td>
            <?php
            foreach (\Yii::$app->authManager->getPermissionsByRole($model->name) as $permission){
                echo $permission->description;
                echo ',';
            }
            ?>
        </td>
        <td><?=\yii\bootstrap\Html::a('修改',['rbac/edit-role','name'=>$model->name],['class'=>'btn btn-warning btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['rbac/role-del','name'=>$model->name],['class'=>'btn btn-danger btn-xs','onclick'=>'return confirm("您确定要删除此角色吗？")'])?></td>
    </tr>
<?php endforeach;?>
    </tbody>
</table>
<?php
$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({

});');
