<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $article backend\models\Article */
/* @var $article_detail backend\models\ArticleDetail */

$this->title = '修改文章: ' . $article->name;
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $article->name, 'url' => ['view', 'id' => $article->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="article-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $article,
        'article_detail'=>$article_detail,
    ]) ?>

</div>
