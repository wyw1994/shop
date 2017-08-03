<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model backend\models\Article */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '文章', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除这条记录吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'intro:ntext',
            'article_category_id',
            'sort',
            'status'=>[
                    'attribute'=>'status',
                'format'=>'raw',
                'value'=>function($model,$stateColor){
                    $stateColor=[-1=>'danger',0=>'warning',1=>'success'];
                    if($model->status==-1){
                        $va=\yii\helpers\Html::tag('span','删除',['class'=>'bg-danger']);
                    }elseif($model->status==0){
                        $va=\yii\helpers\Html::tag('span','隐藏',['class'=>'bg-warning']);
                    }elseif($model->status==1){
                        $va=\yii\helpers\Html::tag('span','正常',['class'=>'bg-success']);
                    }
                    return $va;
                }
            ],
            'creat_time',
        ],
    ]) ?>

</div>
