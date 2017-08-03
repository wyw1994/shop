<?php
    namespace frontend\controllers;
    use frontend\models\PasswordResetRequestForm;
    use Yii;
    use yii\web\Controller;
    use backend\models\Goods;
    use backend\models\GoodsCategory;
    use frontend\models\Member;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Json;
    use yii\web\Response;
    use yii\web\UploadedFile;

    class ApiController extends Controller{
        public $enableCsrfValidation=false;
        public function init(){
            yii::$app->response->format=Response::FORMAT_JSON;
            parent::init();
        }
        //获取品牌下所有商品
        public function actionGetGoodsByBrand(){
            if($brand_id=yii::$app->request->get('brand_id')){
                $goods=Goods::find()->where(['brand_id'=>$brand_id])->asArray()->all();
                return ['static'=>1,'msg'=>'','data'=>$goods];
            }
            return ['status'=>-1,'msg'=>'参数不正确'];
        }
        //会员注册POST
        public function actionUserRegister()
        {
            $request = yii::$app->request;
            if ($request->isPost) {
                $post = $request->post();
                $member = new Member();
                $member->scenario = Member::SCENARIO_API_REGISTER;
                $member->username = $post['username'];
                $member->password = $post['password'];
                $member->email = $post['email'];
                $member->tel = $post['tel'];
                $member->code = $post['code'];
                if ($member->validate()) {
                    $member->save();
                    return ['status' => '1', 'msg' => '', 'data' => $member->toArray()];
                }
                //验证失败
                return ['status' => '-1', 'msg' => $member->getErrors()];
            }
            return ['status' => '-1', 'msg' => '请使用post请求'];
        }
        //登录
        public function actionLogin(){
            $request=yii::$app->request;
            if($request->isPost){
                $post=$request->post();
                $user=Member::findOne(['username'=>$post['username']]);
                if($user&&yii::$app->security->validatePassword($post['password'],$user->password_hash)){
                    yii::$app->user->login($user);
                    return ['status'=>'1','msg'=>'登录成功'];
                }
                return ['status'=>'-1','msg'=>'账号或密码错误'];
            }
            return ['status'=>'-1','msg'=>'请使用post请求'];
        }
        //获取当前登录用户信息、
        public function actionGetCurrentUser(){
            if(yii::$app->user->isGuest){
                return ['status'=>'-1','msg'=>'请先登录'];
            }
            return ['status'=>'1','msg'=>'','data'=>yii::$app->user->identity->toArray()];
        }
        //高级api
        //验证码
        public function actions(){
            return [
              'captcha'=>[
                'class'=>'yii\captcha\CaptchaAction',
                  'fixVerifyCode'=>YII_ENV_TEST?'testme':null,
                  'minLength'=>3,
                  'maxLength'=>3,
              ],
            ];
        }
        //http://www.yii2shop.com/api/captcha.html 显示验证码
        //http://www.yii2shop.com/api/captcha.html?refresh=1 获取新验证码图片地址
        //http://www.yii2shop.com/api/captcha.html?v=59573cbe28c58 新验证码图片地址

        //文件上传
        public function actionUpload(){
            $img=UploadedFile::getInstanceByName('img');
            if($img){
                $fileName='/upload/'.uniqid().'.'.$img->extension;
                $result=$img->saveAs(yii::getAlias('@webroot').$fileName,0);
                if($result){
                    return ['status'=>'1','msg'=>'','data'=>$fileName];
                }
                return ['status'=>'-1','msg'=>$img->error];
            }
            return ['status'=>'-1','msg'=>'没有文件上传'];
        }
        //分页读取数据
        //获取商品列表
        public function actionList(){
            //每页显示条数
            $per_page=yii::$app->request->get('per_page',2);
            //当前页码
            $page=yii::$app->request->get('page',1);
            $keywords=yii::$app->request->get('keywords');
            $page=$page<1?1:$page;
            $query=Goods::find();
            $cate_id=yii::$app->request->get('cat_id');
            $cate=GoodsCategory::findOne(['id'=>$cate_id]);
            if($cate=null){
                return ['status'=>'-1','msg'=>'无此类商品'];
            }
            switch($cate->depth){
                case 2://三级分类
                    $query->andWhere(['goods_category_id'=>$cate_id]);
                break;
                case 1://二级分类
                    $ids=ArrayHelper::map($cate->children,'id','id');
                $query->andWhere(['in','goods_category_id',$ids]);
                break;
                case 0://一级分类
                    $ids=ArrayHelper::map($cate->leaves()->asArray()->all(),'id','id');
                $query->andWhere(['in','goods_category_id',$ids]);
                break;
            }
            if($keywords){
                $query->andWhere(['like','name',$keywords]);
            }
            //总条数
            $total=$query->count();
            //获取当前的商品数据
            $goods=$query->offset($per_page*($page-1))->limit($per_page)->asArray()->all();
            return [
                    'status'=>'1',
                    'msg'=>'',
                    'data'=>[
                        'total'=>$total,
                        'per_page'=>$per_page,
                        'page'=>$page,
                        'goods'=>$goods
                    ]
            ];
        }

        //发送手机验证码
        public function actionSendSms(){
            //确保上一次发短信的时间间隔超过1分钟
            $tel=yii::$app->request->post('tel');
            if(!preg_match('/^1[34578]\d{9}$/',$tel)){
                return ['status'=>'-1','msg'=>'电话号码不正确'];
            }
            //检查上次发送时间是否超过1分钟
            $value=yii::$app->cache->get('time_tel_'.$tel);
            $s=time()-$value;
            if($s<60){
             return ['status'=>'-1','msg'=>'请'.(60-$s).'秒后再试'];
            }
            $code=rand(1000,9999);
            //$result = \Yii::$app->sms->setNum($tel)->setParam(['code' => $code])->send();
            $result=1;
            if($result){
                //保存当前验证码到 session mysql redis 不能存到cookie
                //            \Yii::$app->session->set('code',$code);
//            \Yii::$app->session->set('tel_'.$tel,$code);
                yii::$app->cache->set('tel_'.$tel,$code);
                yii::$app->cache->set('time_tel_'.$tel,time(),5*60);
                //echo 'success'.$code;
                return ['status'=>'1','msg'=>''];
            }else{
                return ['status'=>'-1','msg'=>'短信发送失败'];
            }
        }
    }
