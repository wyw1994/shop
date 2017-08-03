<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $sort
 * @property integer $status
 * @property integer $is_help
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    public static $status_options=[1=>'是',0=>'否'];
    public static $is_help_options=[1=>'帮助',0=>'快讯'];
    public static function tableName()
    {
        return '{{article_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','status','sort','is_help'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status', 'is_help'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['name'],'unique','message'=>'分类已存在']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '分类名',
            'intro' => '简介',
            'sort' => '排序',
            'status' => '启用',
            'is_help' => '类型(帮助/快讯)',
        ];
    }
}
