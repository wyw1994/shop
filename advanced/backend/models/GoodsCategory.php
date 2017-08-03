<?php

namespace backend\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
/**
 * This is the model class for table "goods_category".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
class GoodsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','parent_id'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 255],
            ['name','unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'name' => '商品分类名称',
            'parent_id' => '上级分类',
            'intro' => '介绍',
        ];
    }
    public function behaviors()
    {
        return [
          'tree'=>[
              'class'=>NestedSetsBehavior::className(),
              'treeAttribute'=>'tree',
          ]
        ];
    }
    public function transactions(){
        return [
            self::SCENARIO_DEFAULT=>self::OP_ALL,
        ];
    }
    public static function find(){
        return new GoodsCategoryQuery(get_called_class());
    }
    public static function getZNodes(){
        return array_merge([['id'=>0,'parent_id'=>0,'name'=>'顶级分类']],self::find()->asArray()->all());
    }
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
