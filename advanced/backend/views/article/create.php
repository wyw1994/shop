<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $article backend\models\Article */
/* @var $article_detail backend\models\ArticleDetail */

$this->title = '创建文章';
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $article,
        'article_detail'=>$article_detail,
    ]) ?>

</div>
