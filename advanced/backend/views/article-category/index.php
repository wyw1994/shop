<style>
    th,td{
        text-align:center;
        vertical-align:middle;
    }
    span{
        width:40px;
        height:30px;
    }
</style>
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章类别';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('创建文章类别', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            'intro:ntext',
            'sort',
            'status'=>[
                'label'=>'启用',
                'format'=>'raw',
                'value'=>function($model){
                    $sta=$model->status;
                    $col=[0=>'lightgrey',1=>'lime'];
                    return \yii\bootstrap\Html::tag('span',\backend\models\ArticleCategory::$status_options[$sta],['style'=>'background:'.$col[$sta]]);
                },
            ],
            'is_help'=>[
                    'label'=>'类别',
                    'format'=>'raw',
                    'value'=>function($model){
                        $sta=$model->is_help;
                        $col=[0=>'pink',1=>'lightblue'];
                        return \yii\bootstrap\Html::tag('span',\backend\models\ArticleCategory::$is_help_options[$sta],['style'=>'background:'.$col[$sta]]);
                    }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'buttons' => [
                    ['label'=>'操作'],
                    'view' => function($url, $model, $key) {
                        $options = [
                            'title'=>'查看',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',$url,$options);
                    },
                    'update' => function($url, $model, $key) {
                        $options = [
                            'title'=>'修改',
                        ];
                        return Html::a('<span class="	glyphicon glyphicon-pencil"></span>',$url,$options);
                    },
                    'delete' => function($url, $model, $key){
                        $s=$model->status==1?'禁用此类':'启用此类';
                        $c=$model->status==1?'<span class="glyphicon glyphicon-minus-sign"></span>':'<span class="glyphicon glyphicon-plus-sign"></span>';
                        $d=$model->status==1?'您确定要禁用此文章类别吗?':'您确定要启用此文章类别吗?';
                        $options = [
                            'data-pjax' => 0,
                            'data-confirm' =>$d,
                            'data-method' => 'post',
                            'title'=>$s
                        ];
                        return Html::a($c, $url, $options);
                    }
                ],
            ],
    ]]); ?>
</div>
