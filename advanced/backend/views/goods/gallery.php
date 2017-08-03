<style>
    th,td{
        text-align:center;
        vertical-align:center;
    }
    td > img{
        width:200px;
    }
</style>
<?php
use xj\uploadify\Uploadify;
use yii\bootstrap\Html;
use yii\web\JsExpression;

echo Html::fileInput('test',NULL,['id'=>'test']);
echo Uploadify::widget([
    'url'=>yii\helpers\Url::to(['s-upload']),
    'id'=>'test',
    'csrf'=>true,
    'renderTag'=>false,
    'jsOptions'=>[
        'formData'=>['goods_id'=>$goods->id],//上传文件的同时传参，goods_id
        'width'=>120,
        'height'=>40,
        'onUploadError'=>new JsExpression(
            <<<EOF
            function(file,errorCode,errorMsg,errorString){
                console.log('The file'+'could not be uploaded:'+errorString+errorCode+errorMsg);
            }
EOF

        ),
        'onUploadSuccess'=>new JsExpression(
            <<<EOF
               function(file,data,response){
                data=JSON.parse(data);
                if(data.error){
                    console.log(data.msg);
                }else{
                    console.log(data);
                    //$('#brand-logo').val(data.fileUrl);
                    //$('#img').attr("src",data.fileUrl);
                     var html='<tr data-id="'+data.id+'" id="gallery_'+data.id+'">';
                    html += '<td><img src="'+data.fileUrl+'" /></td>';
                    html += '<td><button type="button" class="btn btn-danger del_btn">删除</button></td>';
                    html += '</tr>';
                    $("table").append(html);
                }
               }  
EOF
        )
    ],
]);
?>
<table class="table table-bordered table-hover table-striped table-responsive">
    <tr>
        <th>图片</th>
        <th>操作</th>
    </tr>
    <?php foreach($galleries as $id=>$path):?>
        <tr id="gallery_<?=$id?>" data-id="<?=$id?>">
            <td><?=Html::img($path)?></td>
            <td><?=Html::button('删除',['class'=>'btn btn-danger del_btn'])?></td>
        </tr>
    <?php endforeach;?>
</table>
<?=Html::a('确定',['goods/index'],['class'=>'btn btn-info'])?>
<?php
    $url=\yii\helpers\Url::to(['del-gallery']);
    $this->registerJs(new JsExpression(
        <<<EOF
        $("table").on('click','.del_btn',function(){
            if(confirm("您确定删除该图片吗？")){
                var id=$(this).closest("tr").attr("data-id");
                $.post("{$url}",{id:id},function(data){
                    console.log(data);
                    if(data=="success"){
                    
                        $("#gallery_"+id).remove();
                        alert('删除成功！');
                    }
                });
            }
        });
EOF
    ));