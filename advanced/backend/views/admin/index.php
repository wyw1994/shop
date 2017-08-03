<?php
/* @var $this yii\web\View */
?>
<style>
    th,td{
        text-align:center;
        vertical-align:center;
        overflow: hidden;
        max-width:100px;
        height:45px;
        line-height:45px;
    }
    th{
        background:#9fc0d0;
    }
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        /* padding: 8px; */
        line-height: 1.42857143;
        vertical-align: middle;
        border-top: 1px solid #ddd;
    }
</style>
<h1 class="bg-info" style="font-family:'楷体'">管理员列表</h1>
<table class="table table-bordered table-striped table-condensed table-responsive table-hover">
    <tr>
    <th>ID</th>
    <th>管理员名字</th>
        <th>角色</th>
    <th>Auth Key</th>
    <th>Password Hash</th>
    <th>Email</th>
    <th>状态</th>
    <th>创建时间</th>
    <th>更新时间</th>
    <th>最后登录时间</th>
    <th>最后登录Ip</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $list):?>
        <tr>
            <td><?=$list->id?></td>
            <td><?=$list->username?></td>
            <td>
                <?php
                    $role=\yii::$app->authManager->getRolesByUser($list->id);
                    if(!empty($role)){
                        $array=\yii\helpers\ArrayHelper::map($role,'name','description');
                        echo implode('-',array_keys($array));
                    }
                ?>
            </td>
            <td><?=$list->password_hash?></td>
            <td><?=$list->auth_key?></td>
            <td><?=$list->email?></td>
            <td><?=\backend\models\Admin::$statusOptions[$list->status]?></td>
            <td><?=date('Ymd',$list->created_at)?></td>
            <td><?=$list->updated_at?></td>
            <td><?=$list->created_at?></td>
            <td><?=$list->last_login_ip?></td>
            <td>
                <?=\yii\bootstrap\Html::a('编辑',['admin/edit','id'=>$list->id],['class'=>'btn btn-success btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['admin/del','id'=>$list->id],['class'=>'btn btn-danger btn-xs','onclick'=>'return confirm("您确定要删除此管理员吗？")'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
