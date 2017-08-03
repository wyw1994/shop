<style>
    .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 0;
        line-height: 1.42857143;
        vertical-align: middle;
        border-top: 1px solid #ddd;
        text-align: center;
        letter-spacing:2px;
    }
    th{
        text-align: center;
        vertical-align: middle;
        background:paleturquoise;
    }
    th,td{
        height:40px;
    }
</style>
<h1  style="font-size:30px;letter-spacing: 2px;color:lightblue;margin-bottom: 10px;">权限列表&nbsp;&nbsp;<?=\yii\bootstrap\Html::a('添加权限',\yii\helpers\Url::to(['rbac/add-permission']),['class'=>'btn btn-info','style'=>'margin-top:4px'])?></h1>
<table class="table  table-bordered table-responsive table-striped table-condensed table-hover">
    <thead>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($models as $model):?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['rbac/edit-permission','name'=>$model->name],['class'=>'btn btn-success btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['rbac/del-permission','name'=>$model->name],['class'=>'btn btn-danger btn-xs','onclick'=>'return confirm("您确定要删除此权限吗？")'])?>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
        'pagination'=>$pager
]);
$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({
});');

