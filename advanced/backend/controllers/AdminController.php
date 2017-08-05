<?php
namespace backend\controllers;
use backend\models\Admin;
use backend\models\LoginForm;
use backend\models\RoleForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AdminController extends BackendController{
    //初始化
    public function actionInit(){
        $admin=new Admin();
        $admin->username="admin123";
        $admin->password = '123456';
//        $admin->password_hash=\yii::$app->security->generatePasswordHash($admin->password_hash);
        $admin->email='2251586313@qq.com';
//        $admin->auth_key=\yii::$app->security->generateRandomString();
//        $admin->created_at=time();
        $admin->save();
        return $this->redirect(['admin/login']);
        //自动完成后自动让用户登录
//        \Yii::$app->user->login($admin);
    }
    //添加
    public function actionAdd(){
        $model=new Admin(['scenario'=>Admin::SCENARIO_ADD]);
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            //var_dump($model->roles);exit;
            $authManager=\yii::$app->authManager;
            $model->save();
            if($model->roles){
                foreach ($model->roles  as $roleName){
                    $role=$authManager->getRole($roleName);
                    $authManager->assign($role,$model->id);
                }
            }
            \yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['admin/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model=Admin::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('账号不存在');
        }
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            $model->save();
            $authManager=\yii::$app->authManager;
            $authManager->revokeAll($model->id);
            $roles=$model->roles;
            if(!empty($roles)){
                foreach($roles as $roleName){
                    $role=$authManager->getRole($roleName);
                    $authManager->assign($role,$model->id);
                }
            }
            \yii::$app->session->setFlash('success','修改成功！');
            return $this->redirect(['admin/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionLogin(){
        $model=new LoginForm();
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            if($model->login()){
                \yii::$app->session->setFlash('success','登录成功');
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }
//        $model=new Admin();
        //指定场景
//        $model->scenario=Admin::SCENARIO_LOGIN;
//        if($model->load(\Yii::$app->request->post())){
//            if($model->validate()){
//                $admin=Admin::findOne(['id'=>1]);
//
//                \yii::$app->user->login($admin,3600*24*7);
//                return $this->redirect(['admin/user']);
//            }
//        }
//        return $this->render('login',['model'=>$model]);
//    }

//管理员列表
public function actionIndex(){
//  if(\yii::$app->user->identity){
    $model=Admin::find()->all();
    return $this->render('index',['model'=>$model]);
}
//}

//删除管理员
public function actionDel($id){
    $model=Admin::findOne(['id'=>$id]);
    if($model&&$model->delete()){
        \yii::$app->session->setFlash('success','删除成功');
        $authManager=\yii::$app->authManager;
        $authManager->revokeAll($id);
        $this->redirect(['admin/index']);
    }
}
//    public function actionUser(){
//        var_dump(\Yii::$app->user->isGuest);
//    }
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['admin/login']);
    }
}