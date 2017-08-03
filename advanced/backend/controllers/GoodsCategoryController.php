<?php

namespace backend\controllers;
use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
class GoodsCategoryController extends BackendController
{
    public function actionIndex(){
        $query=GoodsCategory::find()->orderBy(['tree'=>SORT_ASC,'lft'=>SORT_ASC]);
        $pager = new Pagination([
            'totalCount'=>$query->count(),
            'pageSize'=>5
        ]);
        $models = $query->offset($pager->offset) ->limit($pager->limit) ->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    public function actionAdd()
    {
        $model = new GoodsCategory();
        if ($model->load(\yii::$app->request->post()) && $model->validate()) {
            if ($model->parent_id){
                $parent = GoodsCategory::findOne(['id' => $model->parent_id]);
                $model->prependTo($parent);
            } else {
                $model->makeRoot();
            }
            \yii::$app->session->setFlash('success', '添加成功！');
            return $this->redirect(['goods-category/index']);
        }
        $categories = ArrayHelper::merge([['id' => 0, 'name' => '顶级分类', 'parent_id' => 0]], GoodsCategory::find()->asArray()->all());
        return $this->render('add', ['model' => $model,'categories' => $categories]);
    }
    //修改
    public function actionEdit($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        if(\yii::$app->request->isPost){
           /* if(empty($model->dirtyAttributes)){
               echo  4444;exit;
                return $this->redirect(['goods-category/index']);
            }*/
            if($model->load(\yii::$app->request->post())&&$model->validate()){
                $children=GoodsCategory::findAll(['parent_id'=>$id]);
                if($children){
                    for($i=0;$i<count($children);$i++){
                        if($model->parent_id==$children[$i]->id){
                           \yii::$app->session->setFlash('error','不能修改到自己的子分类下面');
                           return$this->redirect(['goods-category/index']);
                        }
                    }
                }
                //判断否是添加一级分类
                if($model->parent_id&&$model->parent_id!=$id){
                    //添加非一级分类
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);//获取上一级分类
                    $model->prependTo($parent);
                }else{
                    $model->makeRoot();
                }
                \yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods-category/index']);
            }
        }
        $categories=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());

        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
    public function actionZtree(){
//        return $this->renderPartial('ztree');
        $categories=GoodsCategory::find()->asArray()->all();
        return $this->renderPartial('ztree',['categories'=>$categories]);
    }
    public function actionList(){
        $models=GoodsCategory::find()->orderBy('tree,lft')->all();
        return $this->render('list',['models'=>$models]);
    }
    public function actionDelete($id){
        $models=GoodsCategory::find()->where(['id'=>$id])->one();
        if(GoodsCategory::find()->where('parent_id=:id',[':id'=>$id])->all()){
            \yii::$app->session->setFlash('notice','该分类下面有子分类，请删除子分类');
        }else{
            $models->delete();
            \yii::$app->session->setFlash('notice','删除成功！');
        }
        return $this->redirect(['goods-category/index']);
    }
}
