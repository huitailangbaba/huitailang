<?php

namespace backend\models;

use Yii;


class Brand extends \yii\db\ActiveRecord
{

    public static $is=["1"=>"上线","2"=>"下线"];
    public function rules()
    {
        return [
            [["name","intro","logo"],"required"],
            [["sort","status"],"safe"],


        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'logo' => 'LOGO',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
