<style>
    th,td{
        text-align:center;
    }
</style>
<table class="cate table table-bordered table-responsive table-striped">
    <tr>
        <th class="bg-info">ID</th>
        <th class="bg-info">名称</th>
        <th class="bg-info">操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr data-lft="<?=$model->lft?>" data-rgt="<?=$model->rgt?>" data-tree="<?=$model->tree?>" >
            <td><?=$model->id?></td>
            <td>
                <?=str_repeat('——',$model->depth).$model->name?>
                <span class="toggle_cate glyphicon glyphicon-chevron-down" style="float:right"></span>
            </td>
            <td>
                修改 删除
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
    $js=<<<JS
    $('.toggle_cate').click(function(){
        var tr=$(this).closest('tr');
        var tree=parseInt(tr.attr('data-tree'));
        var lft=parseInt(tr.attr('data-lft'));
        var rgt=parseInt(tr.attr('data-rgt'));
        var show =$(this).hasClass('glyphicon-chevron-up');
        $(this).toggleClass('glyphicon-chevron-up');
        $(this).toggleClass('glyphicon-chevron-down');
        $('cate tr').each(function(){
            if(parseInt($(this).attr('data-tree'))==tree&&parseInt($(this).attr('data-lft'))>lft&&parseInt($(this).attr('data-rgt'))<rgt){
                show?$(this).fadeIn():$(this).fadeOut();
            }
        });
    });
JS;
    $this->registerJs($js);
