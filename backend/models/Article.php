<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\17 0017
 * Time: 17:58
 */

namespace backend\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Article extends ActiveRecord
{
    public static $is=["1"=>"上线","2"=>"下线"];
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
            ],
        ];
    }
    public function rules()
    {
        return [
            [["name","intro"],"required"],
            [["sort","status","create_id"],"safe"],
            ["name","unique"]

        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'create_id' => '分类',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
    public function getData(){
        return $this->hasOne(ArticleCategory::className(),["id"=>"create_id"]);
    }
}