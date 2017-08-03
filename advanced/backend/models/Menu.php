<?php
namespace backend\models;
use Yii;
class Menu extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'menu';
    }

    public function rules()
    {
        return [
            ['label','required'],
            ['label','unique'],
            [['parent_id', 'sort'], 'integer'],
            [['label'], 'string', 'max' => 20],
            ['url', 'string', 'max' => 255],
        ];
    }
    public function attributeLabels(){
        return [
          'id'=>'ID',
            'label'=>'名称',
            'url'=>'地址/路由',
            'parent_id'=>'上级菜单',
            'sort'=>'排序'
        ];
    }
    //获得子菜单
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
    public static function getParentOptions(){
        return self::findAll(['parent_id'=>0]);
    }
    public static function getParentName($id){
        $parent_id=Menu::findOne(['id'=>$id])->parent_id;
        $parent=Menu::findOne(['parent_id'=>$parent_id]);
        return $parent->label;
    }
}