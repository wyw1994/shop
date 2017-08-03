<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: middle;
        text-align: center;
        height:60px;
        overflow: hidden;
        text-align:center;
        height:30px;
        border-top: 1px solid #ddd;
        font-family: 'Microsoft Yahei';
    }
    th{
        background:lightcyan;
    }
</style>
<h1 class="bg-info" style="楷体">菜单列表</h1>
<?=\yii\bootstrap\Html::a('添加菜单',['menu/add'],['class'=>'btn btn-success','style'=>'margin-bottom:20px;'])?>
<table class="table table-responsive table-bordered table-striped table-condensed table-hover">
    <thead>
    <tr>
        <th style="min-width:100px">菜单名称</th>
        <th style="min-width:150px">菜单路径</th>
        <th>所属模块</th>
        <th style="min-width: 100px;">操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($model as $model):?>
        <tr>
            <td><?=$model->label?></td>
            <td><?=$model->url?></td>
            <td>
                <?php
                    echo \backend\models\Menu::getParentName($model->id);
                ?>
            </td>
            <td><?=\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['menu/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs','onclick'=>'return confirm("您确定要删除此角色吗？")'])?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php
$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({

});');
