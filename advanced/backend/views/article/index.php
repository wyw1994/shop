<style>
    th{
        text-align:center;
    }
</style>
<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('创建文章', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options'=>[
            'style'=>'text-align:center;vertical-algin:middle'
        ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            'intro:ntext',
            'article_category_id',
            'sort',
            'status'=>[
                'label'=>'状态',
                'format'=>'raw',
                'value'=>function($model){
                    $sta=$model->status;
                    $rs=[-1=>'删除',0=>'隐藏',1=>'正常'];
                    $col=[-1=>'red',0=>'lightgrey',1=>'lime'];
                    return \yii\bootstrap\Html::tag('span',$rs[$sta],['style'=>'background:'.$col[$sta]]);
                },
            ],
            'creat_time',
            [
                'header'=>'操作',
                'class' => 'yii\grid\ActionColumn',
            ],
        ],
    ]); ?>
</div>
