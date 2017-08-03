<?php
namespace frontend\models;
use app\models\OrderGoods;

class Order extends \yii\db\ActiveRecord{
    public static $deliveries=[
        1=>['name'=>'顺丰快递','price'=>25,'detail'=>'速度最快，服务较好，价格稍高'],
        2=>['name'=>'圆通快递','price'=>12,'detail'=>'速度比较快，服务一般，价格便宜'],
        3=>['name'=>'EMS','price'=>15,'detail'=>'速度不定，服务一般']
    ];
    public static $payments=[
      1=>['payment_id'=>1,'payment_name'=>'货到付款','payment_detail'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
        2=>['payment_id'=>2,'payment_name'=>'在线支付','payment_detail'=>'即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        3=>['payment_id'=>3,'payment_name'=>'上门自提','payment_detail'=>'自提时付款，支持现金、POS刷卡、支票支付'],
        4=>['payment_id'=>4,'payment_name'=>'邮局汇款','payment_detail'=>'通过快钱平台收款 汇款后1-3个工作日到账'],
    ];
    public static function tableName(){
        return 'order';
    }
    public function rules(){
        return [
            [['member_id','delivery_id','payment_id','status','create_time'],'integer'],
            [['delivery_price','total'],'number'],
            ['name','string','max'=>50],
            [['province','city','area','delivery_name','payment_name'],'string','max'=>20],
            ['address','string','max'=>255],
            ['tel','string','max'=>11],
            ['trade_no','string','max'=>100],
        ];
    }
    //订单和商品的关系
    public function getGoods(){
        return $this->hasMany(OrderGoods::className(),['order_id'=>'id']);
    }
}