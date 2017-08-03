<?php
use yii\helpers\Html;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model backend\models\Brand */

$this->title = '创建品牌';
$this->params['breadcrumbs'][] = ['label' => 'brand','url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-create">

    <h1 class="bg-danger"><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>


