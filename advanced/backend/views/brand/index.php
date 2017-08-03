<style>
    th{
        text-align:center;
    }
</style>
<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\grid\ActionColumn;
$this->title = '商标列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-index">

    <h1><?= Html::encode($this->title)?></h1>

    <p>
        <?= Html::a('创建商标', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options'=>[
                'style'=>'text-align:center;vertical-algin:middle'
        ],
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            'intro:ntext',
            'logo'=>[
                'label'=>'商标图像',
                'format'=>'raw',
                  'value'=>function($model){
                        if(!empty($model->logo)){
                            $src=$model->logo;
                        }else{
                            $src='./upload/default.jpg';
                        }
                        return \yii\bootstrap\Html::img(
                                $src,
                            [
                                'class'=>'image-circle',
                                'width'=>'80',
                            ]
                        );
                    },
                ],
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
          [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'header' => '操作',
//                'buttons' => [
//                    ['label'=>'操作'],
//                    'view' => function($url, $model, $key) {
//                        return Html::a('查看', $url);
//                    },
//                    'update' => function($url, $model, $key) {
//                        return Html::a('编辑', $url);
//                    },
//                    'delete' => function($url, $model, $key) {
//                        $options = [
//                            'data-pjax' => 0,
//                            'data-confirm' => '您确定要删除此项吗？',
//                            'data-method' => 'post',
//                        ];
//                        return Html::a('删除', $url, $options);
//                    }
//                ],
//            ]
        ],
    ]); ?>
</div>
