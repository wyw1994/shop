<?php
namespace backend\controllers;
use backend\components\SphinxClient;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use xj\uploadify\UploadAction;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
class GoodsController extends BackendController
{
    public function actionIndex()
    {
        $model = new GoodsSearchForm();
        $query = Goods::find();
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
        $cl->SetMatchMode ( SPH_MATCH_ALL);
        $cl->SetLimits(0, 1000);
        if($keyword = \Yii::$app->request->get('keyword')){
            $res = $cl->Query($keyword, 'goods');//shopstore_search
            if(!isset($res['matches'])){
//                throw new NotFoundHttpException('没有找到xxx商品');
                $query->where(['id'=>0]);
            }else{
                //获取商品id
                //var_dump($res);exit;
                $ids = \yii\helpers\ArrayHelper::map($res['matches'],'id','id');
                $query->where(['in','id',$ids]);
            }
        }
            /*if($keyword = \Yii::$app->request->get('keyword')){
                $query->andWhere(['like','name',$keyword]);
            }
            if($sn = \Yii::$app->request->get('sn')){
                $query->andWhere(['like','sn',$sn]);
            }*/

            //接收表单提交的查询参数
            //$model->search($query);
            //商品名称含有"耳机"的  name like "%耳机%"
            //$query = Goods::find()->where(['like','name','耳机']);
            $pager = new Pagination([
                'totalCount'=>$query->count(),
                'pageSize'=>5
            ]);
            $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        if($keyword = \Yii::$app->request->get('keyword')){
            $keywords = array_keys($res['words']);
            $options = array(
                'before_match' => '<span style="color:red;">',
                'after_match' => '</span>',
               'chunk_separator' => '...',
                'limit' => 80, //如果内容超过80个字符，就使用...隐藏多余的的内容
            );
//    //关键字高亮
    //        var_dump($models);exit;
            foreach ($models as $index => $item) {
                $name = $cl->BuildExcerpts([$item->name], 'goods', implode(',', $keywords), $options); //使用的索引不能写*，关键字可以使用空格、逗号等符号做分隔，放心，sphinx很智能，会给你拆分的
                $models[$index]->name = $name[0];
//    //            var_dump($name);
           }
        }

            //var_dump($models);
            //exit;
        return $this->render('index',['models'=>$models,'pager'=>$pager,'model'=>$model]);
    }

    public function  actionAdd(){
        if($editId=\yii::$app->request->get('editId')){
            $model = Goods::findOne(['id'=>$editId]);
            $introModel =GoodsIntro::findOne(['goods_id'=>$editId]);
        }else{
            $model=new Goods();
            $introModel=new GoodsIntro();
        }
        if($model->load(\Yii::$app->request->post())&&$introModel->load(\Yii::$app->request->post())){
            $model->logo_file=UploadedFile::getInstance($model,'logo_file');
            if($model->validate()&&$introModel->validate()){
                if($model->logo_file){
                    $fileName='upload/logo/'.uniqid().''.$model->logo_file->extension;
                    $model->logo_file->saveAs($fileName,false);
                    $model->logo=$fileName;
                }
                //处理sn.自动生成sn，规则为年月日+今日第几个商品
                $day=date('Y-m-d');
                $goodsCount=GoodsDayCount::findOne(['day'=>$day]);
                if($goodsCount==null){
                    $goodsCount=new GoodsDayCount();
                    $goodsCount->day=$day;
                    $goodsCount->count=0;
                    $goodsCount->save();
                }
                $model->sn=date('Ymd').sprintf('%04d',$goodsCount->count+1);
                $model->create_time=time();
                $model->save();
                $introModel->goods_id=$model->id;
                $introModel->save();
                GoodsDayCount::updateAllCounters(['count'=>1],['day'=>$day]);
                \yii::$app->session->setFlash('success','操作成功,请添加商品相册');
                return $this->redirect(['goods/gallery','id'=>$model->id]);
            }
        }
        return $this->render('add',['model'=>$model,'introModel'=>$introModel]);
    }
    //修改
    /*
   * 修改商品信息
   */
    public function actionEdit($id){
        $model = Goods::findOne(['id'=>$id]);
        $introModel =GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('add',['model'=>$model,'introModel'=>$introModel,'editId'=>$id]);
    }
    //商品相册
    public function actionGallery($id){
        $goods=Goods::findOne(['id'=>$id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        $galleries=\yii\helpers\ArrayHelper::map(GoodsGallery::findAll(['goods_id'=>$id]),'id','path');
        return $this->render('gallery',['goods'=>$goods,'galleries'=>$galleries]);
    }
    public function actionDelGallery(){
        $id=\Yii::$app->request->post('id');
        $model=GoodsGallery::findOne(['id'=>$id]);
        if($model&&$model->delete()){
            return 'success';
        }else{
            return 'fail';
        }
    }
    public function actionDel($id){
        $id=\yii::$app->request->get('id');
        $model=Goods::findOne(['id'=>$id]);
        $gallery=GoodsGallery::findAll(['goods_id'=>$id]);
        $goodsIntro=GoodsIntro::findOne(['goods_id'=>$id]);
        if($model&&$model->delete()&&$goodsIntro->delete()){
            foreach ($gallery as $val){
                 $val->delete();
            }
            \yii::$app->session->setFlash('ok','删除成功！');
            return $this->redirect(['goods/index']);
        };
    }
    public function actions(){
        return [
            'upload'=>[
                'class'=>'kucha\ueditor\UEditorAction',
                'config'=>[
                    'imageUrlPrefix'=>'',//图片访问路径前缀
                    'imagePathFormat'=>'/upload/{yyyy}{mm}{dd}/{time}{rand:6}',//上传保存路径
                    'imageRoot'=>\Yii::getAlias('@webroot'),
                ],
            ],

            's-upload'=>[
                'class'=>UploadAction::className(),
                'basePath'=>'@webroot/upload/logo',
                'baseUrl'=>'@web/upload/logo',
                'enableCsrf'=>true,
                'postFieldName'=>'Filedata',
                'overwriteIfExist'=>true,
                'format'=>function(UploadAction $action){
                    $fileext=$action->uploadfile->getExtension();
                    $filehash=sha1(uniqid().time());
                    $p1=substr($filehash,0,2);
                    $p2=substr($filehash,0,2);
                    return "/{$p1}{$p2}{$filehash}.{$fileext}";
                },
                'validateOptions'=>[
                  'extensions'=>['jpg','png','gif'],
                    'maxSize'=>2*1024*1024,
                ],
                'beforeValidate'=>function(UploadAction $action){},
                'afterValidate'=>function(UploadAction $action){},
                'beforeSave'=>function(UploadAction $action){},
                'afterSave'=>function(UploadAction $action){
                    $model=new GoodsGallery();
                    $model->goods_id=\Yii::$app->request->post('goods_id');
                    $model->path=$action->getWebUrl();
                    $model->save();
                    $action->output['fileUrl']=$model->path;
                    $action->output['id']=$model->id;
                }
            ]
        ];
    }
}
