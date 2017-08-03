<?php
namespace frontend\controllers;
use app\models\OrderGoods;
use Yii;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Cart;
use frontend\models\Member;
use yii\db\Exception;
use yii\rbac\Permission;
use yii\web\Controller;
use frontend\models\Address;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use frontend\models\Order;

class MemberController extends Controller
{
    public $layout = 'layout1';
    //用户注册
    public function actionRegister()
    {
        $this->layout = 'login';
        $model = new Member();
        $model->scenario =Member::SCENARIO_REGISTER;
        if ($model->load(\yii::$app->request->post()) && $model->validate()) {
            $model->last_login_ip = ip2long(\yii::$app->request->userIP);
            if ($model->save(false)) {
                \yii::$app->session->setFlash('success', '注册成功,请登录！');
                return $this->redirect(['member/login']);
            }
        }
		
        return $this->render('register', ['model' => $model]);
    }

    //用户登录
    public function actionLogin()
    {
        $this->layout = 'login';
        $model = new Member();
		$model->load(\yii::$app->request->post());
        if ($model->load(\yii::$app->request->post()) && $model->validate()) {
////           $lifeTime=$model->rememberMe?24*3600:0;
////           $session=\Yii::$app->session;
////           session_set_cookie_params($lifeTime);
//           $session['member']=[
//                'memberInfo'=>$model->username,
//                'isLogin'=>1,
//           ];
            if ($model->login()){
                if ( $model->updateAll(['created_at' => time(), 'last_login_ip' => ip2long(\yii::$app->request->userIP)],'username=:user', [':user' => $model->username])){
//                    return $this->redirect(['member/refresh']);
                    //获取查询数据库的条件
                    $member_id=\yii::$app->user->id;
                    $cookies=\yii::$app->request->cookies;
                    $cart=$cookies->get('cart');
                    if(!empty($cart)){
                        //购物车里有购物车数据,则更新到数据库
                        $cart=unserialize($cart->value);
                        foreach ($cart as $goods_id=>$amount){
                            $goods=Goods::findOne(['id'=>$goods_id]);
                            if($goods==null){
                                throw new NotFoundHttpException('商品不存在');
                            }
                            if(empty(Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]))){
                                $model=new Cart();
                                $model->member_id=$member_id;
                                $model->goods_id=$goods_id;
                                $model->amount=$amount;
                                $model->save();
                            }else{
                                Cart::updateAll(['amount'=>$amount],'goods_id=:gid and member_id=:mid',[':gid'=>$goods_id,':mid'=>$member_id]);
                            }
                        }
                    }
                }
                return $this->redirect(\yii::$app->request->referrer);
            }
        }
        return $this->render('login', ['model' => $model]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'maxLength' => 4,
                'minLength' => 4,
                'height' => 40,
                'width' => 80,
            ],
        ];
    }

    //进入address页面
    public function actionAddress()
    {
        if (\yii::$app->user->isGuest) {
            echo '<script>alert("您还没有登陆，请先登录！")</script>';
            return $this->redirect(['member/login']);
        }
        $model = new Address();
        $id = \yii::$app->user->id;
        $model->member_id = \yii::$app->user->id;
        $model->name = \yii::$app->user->identity->username;
        $addressList = Address::findAll(['member_id' => $id]);
        if ($model->load(\yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return $this->redirect(['member/address']);
        }
        return $this->render('address', ['model' => $model, 'addressList' => $addressList]);

    }

    public function actionLogout()
    {
        \yii::$app->user->logout();
        return $this->redirect(['member/login']);
    }

    public function actionSendSms()
    {
        //确保上次发送短信时间超过1分钟
        $tel = \yii::$app->request->post('tel');
        if (!preg_match('/^1[34578]\d{9}$/', $tel)) {
            echo '电话号码不正确';
            exit;
        }
        $code = rand(1000, 9999);
        $result = \yii::$app->sms->setNum($tel)->setParam(['code' => $code])->send();
        if ($result) {
            //保存当前验证码(三种方式，session，mysql,redis)，不可保存在cookie
            \yii::$app->cache->set('tel_' . $tel, $code, 5 * 60);
            echo 'success' . $code;
        } else {
            echo '发送失败';
        }
    }

    //测试短信插件
    public function actionSms()
    {
        //安装插件 composer require flc/alidayu
// 配置信息
        /* $config = [
             'app_key'    => '24479088',
             'app_secret' => 'f28ae4f55611ed14e3ac83d863dba186',
             // 'sandbox'    => true,  // 是否为沙箱环境，默认false
         ];


 // 使用方法一
         $client = new Client(new App($config));
         $req    = new AlibabaAliqinFcSmsNumSend;

         $code = rand(1000,9999);

         $req->setRecNum('18224416499')//设置发给谁（手机号码）
             ->setSmsParam([
                 'code' => $code//${code}
             ])
             ->setSmsFreeSignName('网站开发')//设置短信签名，必须是已审核的签名
             ->setSmsTemplateCode('SMS_71590092');//设置短信模板id，必须审核通过

         $resp = $client->execute($req);
         var_dump($resp);
         var_dump($code);*/
        $code = rand(1000, 9999);
        $result = \yii::$app->sms->setNum(18512315076)->setParam(['code' => $code])->send();
        if ($result) {
            echo $code . '发送成功';
        } else {
            echo '发送失败';
        }
    }

    public function actionMail()
    {
        //通过邮箱重设密码
        $result = \yii::$app->mailer->compose()
            ->setFrom('2251586313@qq.com')//谁的邮箱发出的邮件
            ->setTo('2251586313@qq.com')//发给谁
            ->setSubject('密码重置验证')
            ->setHtmlBody('<b style="color:red">密码重置&nbsp;&nbsp;<a href="http://www.xxx.com/user/resetpass?token=sdfhkjshdfjsdf1243">点此重设密码</a></b>')
            ->send();
    }

    public function actionDelAddress($id)
    {
        $id = (int)(\yii::$app->request->get('id'));
        if (Address::deleteAll(['id' => $id])) {
            $this->redirect(['member/address']);
        }
    }

    //设置默认地址
    public function actionSetDefaultAddress($id)
    {
        $id = \yii::$app->request->get('id');
        $member_id = \yii::$app->request->get('member_id');
        Address::updateAll(['is_default' => 0], 'member_id=:member_id', [':member_id' => $member_id]);
        $model = Address::findOne(['id' => $id]);
        $model->is_default = 1;
        if ($model->update(['is_default']) !== false) {
            return $this->redirect(['member/address']);
        }
    }
    //商品种类列表页
    public function actionList()
    {
        $cate_id = \yii::$app->request->get('cate_id');
        $cate_id=isset($cate_id)?(int)$cate_id:null;
        $cate=null;
        $get_brand_id=0;
        if(!empty($cate_id)){
            $cate = GoodsCategory::findOne(['id' => $cate_id]);
        }else{
            $brand_id=\yii::$app->request->get('brand_id');
            $brand_id=isset($brand_id)?(int)$brand_id:1;
            if(!empty($brand_id)){
                $cur_goods=Goods::find()->where(['brand_id'=>$brand_id])->asArray()->one();
                //有brand_id则必有一个对应的goods_category_id，则通过这个去找父id
                $cur_cate_id=$cur_goods['goods_category_id'];
                $cate=GoodsCategory::findOne(['id'=>$cur_cate_id]);
                if(!empty($cate)){
                    $cate_id=$cate->parent_id;
                    $cate=GoodsCategory::findOne(['id'=>$cate_id]);
                    if(!empty($cate)){
                        $cate_id=$cate->parent_id;
                        $cate=GoodsCategory::findOne(['id'=>$cate_id]);
                    }
                }
            }
        }
        //获取自己和下级分类的id，name
        $catesGroupByPid = \yii\helpers\ArrayHelper::map(GoodsCategory::find()->where('tree=:tree and lft >=:lft and rgt <=:rgt', [':tree' => $cate->tree, ':lft' => $cate->lft, ':rgt' => $cate->rgt])->all(), 'id', 'name', 'parent_id');
        $catesGroupByDepth = \yii\helpers\ArrayHelper::map(GoodsCategory::find()->where('tree=:tree and lft >=:lft and rgt <=:rgt', [':tree' => $cate->tree, ':lft' => $cate->lft, ':rgt' => $cate->rgt])->all(), 'id', 'name', 'depth');
        //获取自己和上级分类的id，name，按从上级到下级顺序排列
        $menu = [];
        $parent = GoodsCategory::findOne(['id' => $cate->parent_id]);
        $graParent = null;
        if (!empty($parent)) {
            $graParent = GoodsCategory::findOne(['id' => $parent->parent_id]);
        }
        if (!empty($graParent)) {
            $menu[] = ['id' => $graParent->id, 'name' => $graParent->name];
        }
        if (!empty($parent)) {
            $menu[] = ['id' => $parent->id, 'name' => $parent->name];
        }
        $menu[] = ['id' => $cate->id, 'name' => $cate->name];
        //获取本类和下级类的ID集合
        $cateIds = array_column(GoodsCategory::find()->where('tree=:tree and lft >=:lft and rgt <=:rgt', [':tree' => $cate->tree, ':lft' => $cate->lft, ':rgt' => $cate->rgt])->select('id')->asArray()->all(), 'id');
        $goodsByCates = [];
        foreach ($cateIds as $cateId) {
            $goodsInfo = Goods::find()->where('goods_category_id =:cateId', [':cateId' => $cateId])->select(['id', 'name', 'goods_category_id', 'brand_id', 'logo', 'status', 'shop_price'])->asArray()->all();
            if ($goodsInfo) {
                $goodsByCates= array_merge($goodsByCates, $goodsInfo);
            }
        }
        $goodsByBrand= [];
        $goodsOfStatus=[];
        foreach ($goodsByCates as $goodsinfo) {
            $goodsByBrand[$goodsinfo['brand_id']][] = $goodsinfo;
            $goodsOfStatus[$goodsinfo['status']][]=$goodsinfo;
        }
        //获取所有对应产品的品牌
        $brandIds=array_keys($goodsByBrand);
        $brands=Brand::find()->select(['id','name'])->where(['in','id',$brandIds])->asArray()->all();
        if(!empty($brand_id)){
            $goodsByBrand=Goods::find()->select(['id', 'name', 'goods_category_id', 'brand_id', 'logo', 'status', 'shop_price'])->where(['brand_id'=>$brand_id])->asArray()->all();
            $get_brand_id=1;
        }
        if(!empty($goodsOfStatus[3])){
            $goodsOfHot=$goodsOfStatus[3];
        }else{
            $goodsOfHot='';
        }
        if(!empty($goodsOfStatus[2])){
            $goodsOfNew=$goodsOfStatus[2];
        }else{
            $goodsOfNew='';
        }

        return $this->render('list',['menu'=>$menu,'brands'=>$brands,'goodsByCates'=>$goodsByCates,'goodsByBrand'=>$goodsByBrand,'goodsOfHot'=>$goodsOfHot,'goodsOfNew'=> $goodsOfNew,'catesGroupByDepth'=>$catesGroupByDepth,'catesGroupByPid'=>$catesGroupByPid,'get_brand_id'=>$get_brand_id]);
    }
    //商品详细页面
    public function actionGoodsDetail($id){
        $id=\yii::$app->request->get('id');
        $good=Goods::findOne(['id'=>$id]);
        return $this->render('goods',['good'=>$good]);
    }
    //添加商品到购物车
    public function actionAddGood(){
        $post=\Yii::$app->request->post();
        $goods_id=$post['goods_id'];
        $amount=$post['amount'];
        $goods=Goods::findOne(['id'=>$goods_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        if(\Yii::$app->user->isGuest){
            //先获取cookie中的购物车数据
            $cart=\yii::$app->request->cookies->get('cart');
            if($cart==null){
                //cookie中没有购物车数据
                $cart=[];
            }else{
                $cart=unserialize($cart->value);
            }
            //将商品id和数量保存到cookie
    //            $cart=[
    //                ['id'=>2,'amount'=>10],
    //                ['id'=>1,'amount'=>3]
    //            ];
            //检查购物车中是否有该商品,有则数量增加
            if(key_exists($goods->id,$cart)){
                $cart[$goods_id]+=$amount;
            }else{
                $cart[$goods_id]=$amount;
            }
            $cookie=new Cookie([
                'name'=>'cart',
                'value'=>serialize($cart)
            ]);
            \Yii::$app->response->cookies->add($cookie);
        }
        else{
            $member_id=\yii::$app->user->id;
            //购物车里有购物车数据,则更新到数据库
            if(empty(Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]))){
                $model=new Cart();
                $model->member_id=$member_id;
                $model->goods_id=$goods_id;
                $model->amount=$amount;
                $model->save();
            }else{
                Cart::updateAll(['amount'=>$amount],'goods_id=:gid and member_id=:mid',[':gid'=>$goods_id,':mid'=>$member_id]);
            }
        }
        return $this->redirect(['member/cart']);
    }
    //购物车
    public function actionCart(){
            $models=[];
            if(\yii::$app->user->isGuest){
                //取出cookie中的商品id和数量
                $cookie=\yii::$app->request->cookies->get('cart');
                if($cookie==null){
                    //cookie中没有购物车数据
                    $cart=[];
                }else{
                    $cart=unserialize($cookie->value);
                    foreach ($cart as $good_id => $amount) {
                        $goods = Goods::findOne(['id' => $good_id])->attributes;
                        $goods['amount'] = $amount;
                        $models[] = $goods;
                    }
                }
            } else{
                $models=[];
                //从数据库获取购物车数据
                $member_id=\yii::$app->user->id;
                $cart=Cart::find()->select(['goods_id','amount'])->where(['member_id'=>$member_id])->asArray()->all();
                //把二维的$cart数组遍历成一维的数组，保持和cookie里存值格式一样
                $cartInfo=[];
                foreach ($cart as $v){
                    $cartInfo[$v['goods_id']]=$v['amount'];
                }
                $cart=$cartInfo;
                if(!empty($cart)){
                    foreach ($cart as $good_id=>$amount){
                        $goods=Goods::findOne(['id'=>$good_id]);
                        if(!empty($goods)){
                            $good=$goods->attributes;
                            $good['amount']=$amount;
                            $models[]=$good;
                        }
                    }
                }
            }
            return $this->render('cart',['models'=>$models]);
        }

    public function actionUpdateCart(){
        $post=\yii::$app->request->post();
        $goods_id=$post['goods_id'];
        $amount=$post['amount'];
        $goods=Goods::findOne(['id'=>$goods_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        //未登录，则先获取cookie中的购物车数据
        if(\Yii::$app->user->isGuest){
            $cart=\yii::$app->request->cookies->get('cart');
            if($cart==null){
                //cookie中没有购物车数据
                $cart=[];
            }else{
                $cart=unserialize($cart->value);
            }
            //检查购物车中是否有该商品,有，数量累加
            /*if(key_exists($goods->id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                $cart[$goods_id] = $amount;
            }*/
            if($amount){
                $cart[$goods_id]=$amount;
            }else{
                if(key_exists($goods['id'],$cart)){
                    unset($cart[$goods_id]);
                }
            }
            $cookie=new Cookie([
                'name'=>'cart',
                'value'=>serialize($cart)
            ]);
            \yii::$app->response->cookies->add($cookie);
            }else{
                //return $this->redirect(['member/refresh']);
                //获取查询数据库的条件
                $member_id=\yii::$app->user->id;
                if(empty(Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]))){
                    $model=new Cart();
                    $model->member_id=$member_id;
                    $model->goods_id=$goods_id;
                    $model->amount=$amount;
                    $model->save();
                }else{
                    if($amount==0){
                        Cart::deleteAll('goods_id=:gid and member_id=:mid',[':gid'=>$goods_id,':mid'=>$member_id]);
                    }else{
                        Cart::updateAll(['amount'=>$amount],'goods_id=:gid and member_id=:mid',[':gid'=>$goods_id,':mid'=>$member_id]);
                    }
                }
            }
            return $this->redirect(['member/cart']);
        }
//登录后更新cart到数据库的复用代码
protected function actionRefresh(){
    //获取查询数据库的条件
    $member_id=\yii::$app->user->id;
    $cookies=\yii::$app->request->cookies;
    $cart=$cookies->get('cart');
    if(!empty($cart)){
        //购物车里有购物车数据,则更新到数据库
        $cart=unserialize($cart->value);
        foreach ($cart as $goods_id=>$amount){
            $goods=Goods::findOne(['id'=>$goods_id]);
            if($goods==null){
                throw new NotFoundHttpException('商品不存在');
            }
            if(empty(Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]))){
                $model=new Cart();
                $model->member_id=$member_id;
                $model->goods_id=$goods_id;
                $model->amount=$amount;
                $model->save();
            }else{
                Cart::updateAll(['amount'=>$amount],'goods_id=:gid and member_id=:mid',[':gid'=>$goods_id,':mid'=>$member_id]);
            }
        }
    }
}
//检查订单
public function actionOrder(){
    $this->layout='layout2';
    if(\yii::$app->user->isGuest){
        \yii::$app->session->setFlash('error','请先登录');
        return $this->redirect(['member/login']);
    }
    if(\yii::$app->request->isGet){
        $member_id=\yii::$app->user->id;
        $cart=Cart::find()->where('member_id=:mid',['mid'=>$member_id])->asArray()->all();
        $address=Address::find()->where('member_id=:mid',[':mid'=>$member_id])->asArray()->all();
        $goodsInfos=[];
        foreach ($cart as $cartItem){
            $goodsInfo=[];
            $goods_id=$cartItem['goods_id'];
            $goods_amount=$cartItem['amount'];
            $goods=Goods::findOne(['id'=>$goods_id]);
            if(!empty($goods)){
                $dataGoods=$goods->attributes;
                $dataIntro=$goods->getGoodsIntro()->asArray()->one();
                $goodsIntro=$dataIntro['content'];
                $goodsInfo=$dataGoods;
                $goodsInfo['intro']=$goodsIntro;
                $goodsInfo['amount']=$goods_amount;
                $goodsInfos[]=$goodsInfo;
            }
        }
        return $this->render('order',['address'=>$address,'goodsInfos'=>$goodsInfos]);
    }
        if(Yii::$app->request->isPost){
            $model=new Order();
            //继续获取其他属性的值
            $post=Yii::$app->request->post();
            $address_id=$post['address_id'];
            $address=Address::findOne(['id'=>$address_id,'member_id'=>Yii::$app->user->id]);
            if($address==null){
                throw new NotFoundHttpException('地址不存在');
            }
            $model->member_id=Yii::$app->user->id;
            $model->name=$address->name;
            $model->province=$address->province;
            $model->city=$address->city;
            $model->area=$address->county;
            $model->address=$address->detail;
            $model->tel=$address->tel;
            //送货方式
            $index=$post['delivery_id'];
            $model->delivery_id=$index;
            $model->delivery_name=Order::$deliveries[$index]['name'];
            $model->delivery_price=Order::$deliveries[$index]['price'];
            $model->status=1;
            $model->create_time=time();
            $model->payment_id=$post['payment_id'];
            $model->payment_name=Order::$payments[$model->payment_id]['payment_name'];
            //计算总价格
            //遍历购物车商品，循环累加
            //$model->total
            //回滚-事务-innodb的存储引擎
            //开启事务
            $transaction=Yii::$app->db->beginTransaction();
            try{
                $model->save();
                $order_id=$model->getPrimaryKey();
                //$model->id保存后就有id属性
                //订单商品详情表
                //根据购物车数据，把商品的详情查询出来，逐条保存
                $carts=Cart::find()->where(['member_id'=>Yii::$app->user->id])->asArray()->all();
                foreach($carts as $cart){
                    $goods=Goods::find()->where('id=:cate_gid',[':cate_gid'=>$cart['goods_id']])->andWhere(['in','status',[1,2,3]])->One();
                    if($goods==null){
                        //商品不存在
                        $goods_name=Goods::findOne(['id'=>$cart['goods_id']])->name;
                        throw new Exception('商品'.$goods_name.'已售完');
                    }
                    if($goods->stock<$cart['amount']){
                        //库存不足
                        $goods_name=Goods::findOne(['id'=>$cart['goods_id']])->name;
                        throw new Exception('商品'.$goods_name.'库存不足');
                    }
                    $order_goods=new OrderGoods();
//                $order_goods->order_id=$model->id;
                    $order_goods->logo=$goods->logo;
                    $order_goods->order_id=$order_id;
                    $order_goods->amount=$cart['amount'];
                    $order_goods->price=$goods->shop_price;
                    $order_goods->total=$order_goods->price*$order_goods->amount;
                    $gid=$cart['goods_id'];
                    $order_goods->goods_id=$gid;
                    $order_goods->save();
                    //扣减该商品的库存
                    $goods->stock-=$cart['amount'];
                    Cart::deleteAll('goods_id=:gid',[':gid'=>$gid]);
                }
                //提交
                $transaction->commit();
                return $this->redirect(['member/pay','order_id'=>$order_id]);
            }catch(Exception $e){
                //回滚
                $transaction->rollBack();
            }
            return $this->redirect(['member/cart','order_id'=>$order_id]);
        }
    }
//提交订单
public function actionCheck(){
    if(\yii::$app->user->isGuest){
        \yii::$app->session->setFlash('error','请先登录');
        return $this->redirect(['member/login']);
    }
    $model=new Order();
    if(Yii::$app->request->isPost){
        $post=Yii::$app->request->post();
        //继续获取其他属性的值
        $address_id=Yii::$app->request->post()['address_id'];
        $address=Address::findOne(['id'=>$address_id,'member_id'=>Yii::$app->user->id]);
        if($address==null){
            throw new NotFoundHttpException('地址不存在');
        }
        $model->member_id=Yii::$app->user->id;
        $model->province=$address->province;
        $model->city=$address->city;
        $model->area=$address->county;
        $model->address=$address->detail;
        $model->tel=$address->tel;
        //送货方式
        $model->delivery_name=Order::$deliveries[$model->delivery_id]['name'];
        $model->delivery_price=Order::$deliveries[$model->delivery_id]['price'];
        $model->status=1;
        $model->create_time=time();
        //计算总价格
        //遍历购物车商品，循环累加
        //$model->total
        //回滚-事务-innodb的存储引擎
        //开启事务
        $transaction=Yii::$app->db->beginTransaction();
        try{
            if(!$model->save()){
                throw new \Exception();
            }
            $order_id=$model->getPrimaryKey();
            //$model->id保存后就有id属性
            //订单商品详情表
            //根据购物车数据，把商品的详情查询出来，逐条保存
            $carts=Cart::findAll(['member_id'=>Yii::$app->user->id]);
            foreach($carts as $cart){
                $goods=Goods::findOne(['id'=>$cart->goods_id,'status'=>'1,2,3']);
                if($goods==null){
                    //商品不存在
                    $goods_name=$goods=Goods::findOne(['id'=>$cart->goods_id])->name;
                    throw new Exception('商品 '.$goods_name.' 已售完');
                }
                if($goods->stock<$cart->amount){
                    //库存不足
                    $goods_name=$goods=Goods::findOne(['id'=>$cart->goods_id])->name;
                    throw new Exception('商品 '.$goods_name.' 库存不足');
                }
                $order_goods=new OrderGoods();
//                $order_goods->order_id=$model->id;
                $order_goods->total=$order_goods->price*$order_goods->amount;
                $order_goods->save();
                $gid=$order_goods->getPrimaryKey();
                //扣减该商品的库存
                $goods->stock-=$cart->amount;
                $goods->save();
                Cart::deleteAll(['goods_id=:gid',[':gid'=>$gid]]);
            }
            //提交
            $transaction->commit();
        }catch(Exception $e){
            //回滚
            $transaction->rollBack();
            return $this->redirect(['member/cart']);
        }
        return $this->redirect(['member/check','order_id'=>$order_id]);
    }
}
//清理超时未支付的订单
public function actionClean(){
    set_time_limit(0);//不限制脚本执行时间
    while(1){
        //超时未支付订单。 状态status： 待支付状态1 已取消0 待发货2 待收货3 完成4
        $models=Order::find()->where(['status'=>1])->andWhere(['<','create_time',time()-3600])->all();
        foreach($models as $model){
            //$model->status=0;
            //$model->save();
            //返还库存
            foreach($model->goods as $goods){
                Goods::updateAllCounters(['stock'=>$goods->amount],'id='.$goods->goods_id);
            }
            echo 'ID为'.$model->id.'的订单被取消了';
        }
        //1秒钟执行一次
        sleep(1);
    }
}
//        public function actionSetcookie(){
//            \Yii::$app->response->cookies->add(new Cookie(['name'=>'zhansgan','value'=>'5454']));
//        }
//        public function actionGetcookie(){
//            $res = \Yii::$app->request->cookies->get('zhansgan')->value;
//            var_dump($res);
//        }
}