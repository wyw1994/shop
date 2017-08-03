<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Cart extends ActiveRecord {
    public static function tableName(){
        return "{{%cart}}";
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           [['goods_id','amount','member_id'],'required'],
        ];
    }
}