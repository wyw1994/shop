<?php
namespace backend\controllers;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class RbacController extends BackendController{
    //添加权限
    public function actionAddPermission(){
        $model=new PermissionForm();
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            if($model->addPermission()){
                \yii::$app->session->setFlash('success','权限添加成功！');
                return $this->redirect(['permission-index']);
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    //修改权限
    public function actionEditPermission($name){
        $permission=\yii::$app->authManager->getPermission($name);
        if($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        $model=new PermissionForm();
        //将要修改的权限的值赋值给表单模型
        $model->loadData($permission);
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            if($model->updatePermission($name)){
                \yii::$app->session->setFlash('success','权限修改成功');
                return $this->redirect(['permission-index']);
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    //权限列表
    public function actionPermissionIndex()
    {
        $models = \Yii::$app->authManager->getPermissions();
        $total=count($models);
        $pager=new Pagination(
            [
                'totalCount'=>$total,
                'pageSize'=>100
            ]
        );
        $models=array_slice($models,$pager->offset,$pager->limit,true);
        return $this->render('permission-index',['models'=>$models,'pager'=>$pager]);
    }
    //删除权限
    public function actionDelPermission($name){
        $permission=\yii::$app->authManager->getPermission($name);
        if($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        \yii::$app->authManager->remove($permission);
        \yii::$app->session->setFlash('success','权限删除成功');
        return $this->redirect(['permission-index']);
    }
    //角色的增删改查
    //创建角色
    public function actionAddRole(){
        $model=new RoleForm();
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            if($model->addRole()){
                \yii::$app->session->setFlash('success','角色添加成功');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    //修改角色
    public function actionEditRole($name){
        $role=\yii::$app->authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
        $model=new RoleForm();
        //var_dump($role,\yii::$app->request->post());exit;
        $model->loadData($role);
        //var_dump($model);exit;
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            if($model->updateRole($name)){
                \yii::$app->session->setFlash('success','角色修改成功');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    //删除角色
    public function actionRoleDel($role){
        $role = \Yii::$app->authManager->getRole($role);
        // print_r($role);exit;
        if (\Yii::$app->authManager->remove($role)) {
            return $this->redirect(['role-index']);
        }else{
            \Yii::$app->session->setFlash('error','删除失败');
        }
    }
    //角色列表
    public function actionRoleIndex(){
        $models=\yii::$app->authManager->getRoles();
        return $this->render('role-index',['models'=>$models]);
    }
}