<?php
namespace backend\controllers;
use Yii;
use backend\models\Article;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use backend\models\ArticleDetail;
/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends BackendController
{
    public function actionIndex()
    {
//        $query=Article::find();
//        $pager=new Pagination(
//            [
//                'totalCount'=>$query->count(),
//                'pageSize'=>3,
//            ]
//        );
        $dataProvider=new ActiveDataProvider([
//            'query'=>Article::find()->where(['status'=>1])->orderBy('sort desc'),
            'query'=>Article::find()->orderBy('sort desc'),
           'pagination'=>[
               'pageSize'=>3,
           ]
        ]);
//        $articles=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate()
//    {
////        $model = new Article();
////        if ($model->load(Yii::$app->request->post()) && $model->save()) {
////            return $this->redirect(['view', 'id' => $model->id]);
////        } else {
////            return $this->render('create', [
////                'model' => $model,
////            ]);
////        }
//        $article=new Article();
//        $article_detail=new ArticleDetail();
//        if($article->load(Yii::$app->request->post())&&$article_detail->load(Yii::$app->request->post())&&$article->validate()&&$article_detail->validate()){    var_dump($article,$article_detail);exit;
//            $article->save();
//            $article_detail->article_id=$article->id;
//            $article_detail->save();
//            Yii::$app->session->setFlash('success','文章添加成功');
//            return $this->redirect(['index']);
//        }
//        return  $this->render('create',['model'=>$article,'article_detail'=>$article_detail]);
//   }

    public function actionCreate()
    {
        $article = new Article();
        $article_detail = new ArticleDetail();
        if($article->load(\Yii::$app->request->post())
            && $article_detail->load(\Yii::$app->request->post())
            && $article->validate()){
            $article->save();
            $article_detail->article_id = $article->id;
            if($article_detail->validate()){
                $article_detail->save();
            }
            \Yii::$app->session->setFlash('success','文章添加成功');
            return $this->redirect(['index']);
        }
//        $categories = ArticleCategory::find()->asArray()->where(['status'=>1])->all();
        /*$options = [];
        foreach ($categories as $cate){
            $options[$cate['id']] = $cate['name'];
        }*/
//        $options = ArrayHelper::map($categories,'id','name');
        return $this->render('create',['article'=>$article,'article_detail'=>$article_detail]);
    }
    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $article = Article::findOne(['id'=>$id]);
        $article_detail = $article->detail;
        if ($article->load(Yii::$app->request->post()) && $article->save()&&$article_detail->load(Yii::$app->request->post())&&$article_detail->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'article' =>$article,
                'article_detail'=>$article_detail
            ]);
        }
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
