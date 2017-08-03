<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property string $goods_category_id
 * @property string $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property string $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property string $sort
 * @property string $create_time
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $logo_file;
    public static $sale_options=[1=>'上架',0=>'下架'];
    public static $status_options=[1=>'正常',0=>'删除',2=>'新品',3=>'热卖'];
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','goods_category_id', 'brand_id','is_on_sale', 'market_price', 'shop_price', 'stock'], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 50],
            ['logo', 'string', 'max' => 255],
            ['logo','default','value'=>'./upload/logo/default.jpg'],
            [['sn'], 'unique'],
            [['logo_file'],'file','extensions'=>['jpg','png','gif']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'Logo',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '在售/下架',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => 'Create Time',
        ];
    }
    //品牌选项
    public static function getBrandOptions(){
        return ArrayHelper::map(Brand::find()->asArray()->all(),'id','name');
    }
    //商品和相册1对多的关系
    public function getGalleries(){
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id']);
    }
    //获取商品详情
    public function getGoodsIntro(){
        return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);
    }
    public static function getNameOfOneGoodsCategory($cateId){
        return GoodsCategory::findOne(['id'=>$cateId]);
    }
    public static function getNameOfOneGoodsBrand($brandId){
        return Brand::findOne(['id'=>$brandId]);
    }

}
