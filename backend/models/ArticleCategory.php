<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\16 0016
 * Time: 14:13
 */

namespace backend\models;


use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord

{
    public static $is=["1"=>"上线","2"=>"下线"];
    public static $as=["1"=>"是","2"=>"不是"];
    public function rules()
    {
        return [
            [["name","intro"],"required"],
            [["sort","status","is_help"],"safe"],
            ["name","unique"]

        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'is_help' => '是否是帮助类',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}