<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Brand */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Brands', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'id'=>[
                'attribute'=>'id',
                'label'=>'ID',
            ],
            'name',
            'intro:ntext',
            'logo'=>[
                'attribute'=>'logo',
                'format'=>'raw',
                'value'=>function($model) {

                    return $model->logo?\yii\bootstrap\Html::img($model->logo,['style'=>'width:80px;height:80px;']):\yii\bootstrap\Html::img(Yii::getAlias('@web').'/upload/default.jpg',['style'=>'width:80px;height:80px;']);
                },
            ],
            'sort',
            'status'=>[
                'attribute'=>'status',
                'format'=>'raw',
                'value'=>function($model){
                    if($model->status==-1){
                        $va=\yii\bootstrap\Html::tag('span','删除',['class'=>'bg-danger']);
                    }elseif($model->status==0){
                        $va=\yii\bootstrap\Html::tag('span','隐藏',['style'=>'lightgrey']);
                    }elseif($model->status==1){
                        $va=\yii\bootstrap\Html::tag('span','正常',['class'=>'bg-success']);
                    }
                    return $va;
                }
            ],
        ],
    ]) ?>

</div>
