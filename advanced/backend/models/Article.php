<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $status
 * @property string $creat_time
 */
class Article extends \yii\db\ActiveRecord
{
    public static $static_options=[-1=>'删除',0=>'隐藏',1=>'正常'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intro'], 'required'],
            [['intro'], 'string'],
            [['article_category_id', 'sort', 'status'], 'integer'],
            [['creat_time'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '文章ID',
            'name' => '文章名',
            'intro' => '简介',
            'article_category_id' => '文章分类ID',
            'sort' => '排序',
            'status' => '状态',
            'creat_time' => '创建时间',
        ];
    }
    public static function getCategoryOptions(){
        return ArrayHelper::map(ArticleCategory::find()->where(['status'=>1])->asArray()->all(),'id','name');
    }
    public function getCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
    public function getDetail(){
        return $this->hasOne(ArticleDetail::className(),['article_id'=>'id']);
    }
}
