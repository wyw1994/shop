<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ArticleCategory */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '文章类别', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$words=$model->status == 1 ? '你确定要禁用此类吗？' : '你确定要启用此类吗？';
?>
<div class="article-category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a($model->status==1?'禁用此类':'启用此类', ['delete', 'id' => $model->id],[
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' =>$words,
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
        ],
    ]) ?>

</div>
