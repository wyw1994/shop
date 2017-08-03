<?php
namespace backend\models;
use yii\base\Model;
use yii\data\Pagination;
use yii\db\ActiveQuery;
class GoodsSearchForm extends Model{
    public $name;
    public $sn;
    public $minPrice;
    public $maxPrice;
    public function rules(){
        return [
          ['name','string','max'=>50],
          ['sn','string'],
          ['minPrice','double'],
            ['maxPrice','double']
        ];
    }
    public function search(ActiveQuery $query,$search_form=null){
        $this->load(\Yii::$app->request->get(),$search_form);
        if($this->name){
            $query->andWhere(['like','name',$this->name]);
        }
        if($this->sn){
            $query->andWhere(['like','sn',$this->sn]);
        }
        if($this->maxPrice){
            $query->andWhere(['<=','shop_price',$this->maxPrice]);
        }
        if($this->minPrice){
            $query->andWhere(['>=','shop_price',$this->minPrice]);
        }
    }
}