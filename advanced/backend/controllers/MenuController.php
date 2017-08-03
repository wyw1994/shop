<?php

namespace backend\controllers;
use backend\models\Menu;
class MenuController extends BackendController
{
    public function actionIndex()
    {
        $model=Menu::find()->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd(){
        $model=new Menu();
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            $model->save();
            \yii::$app->session->setFlash('success','添加成功！');
           return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=Menu::findOne(['id'=>$id]);
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            $model->save();
            \yii::$app->session->setFlash('success','修改成功！');
           return  $this->redirect(['menu/index']);
        }
        $this->render('add',['model'=>$model]);
    }
    public function actionDel($id){
        $model=Menu::findOne($id);
        $childNum=count($model->children);
        if($childNum){
            \yii::$app->session->setFlash('error','该分类下有子分类，请先删除子分类');
            return $this->redirect(['menu/index']);
        }
        if($model&&$model->delete()){
            \yii::$app->session->setFlash('success','删除成功！');
            return $this->redirect(['menu/index']);
        }
    }
}
