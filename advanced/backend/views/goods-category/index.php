<style>
    th,td{
        text-align:center;
    }
    td:nth-child(2){
        text-align:left;
    }
    a{
        margin-right:5px;
    }
</style>
<?php
/* @var $this yii\web\View */
?>
<?=\yii\bootstrap\Html::a('添加商品类',['goods-category/add'],['class'=>'btn btn-info',
'style'=>'margin-bottom:10px;'])?>
<table class="table table-bordered table-responsive table-striped">
    <tr>
        <th class="bg-success">ID</th>
        <th class="bg-success">名称</th>
        <th class="bg-success">操作</th>
    </tr>
    <tbody id="category">
    <?php foreach($models as $model):?>
    <tr data-lft="<?=$model->lft?>" data-rgt="<?=$model->rgt?>" data-tree="<?=$model->tree?>">
        <td><?=$model->id?></td>
        <td><?=str_repeat(' —— ',$model->depth).$model->name?>
            <span class="glyphicon glyphicon-chevron-down expand" style="float:right">
            </span>
        </td>
        <td>
            <?=\yii\bootstrap\Html::a('<span class="glyphicon glyphicon-pencil"></span>',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-info btn-md','title'=>'修改'])?>
            <?=\yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash"></span>',['goods-category/delete','id'=>$model->id],['class'=>'btn btn-danger btn-md','title'=>'删除'])?>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php echo \yii\widgets\LinkPager::widget([
        'pagination' => $pager,
        'nextPageLabel' => '下一页',
        'prevPageLabel' => '上一页',
        'firstPageLabel' => '首页',
        'lastPageLabel' => '尾页',
    ]
)?>
<?php
$js=<<<EOT
    $(".expand").click(function(){
        var show =$(this).hasClass("glyphicon-chevron-up");
        $(this).toggleClass("glyphicon-chevron-down");
        $(this).toggleClass("glyphicon-chevron-up");
        var current_tr=$(this).closest("tr");
        var current_lft=current_tr.attr("data-lft");
        var current_rgt=current_tr.attr("data-rgt");
        var current_tree=current_tr.attr("data-tree");
        $("#category tr").each(function(){
            var lft=$(this).attr("data-lft");
            var rgt=$(this).attr("data-rgt");
            var tree=$(this).attr("data-tree");
            if(parseInt(tree)==parseInt(current_tree)&&parseInt(lft)>parseInt(current_lft)&&parseInt(rgt)<parseInt(current_rgt)){
                show?$(this).fadeIn():$(this).fadeOut();
            }
        });
    });
EOT;
$this->registerJs($js);
