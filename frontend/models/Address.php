<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property string $name 姓名
 * @property int $user_id 用户id
 * @property string $province 省
 * @property string $city 市
 * @property string $county 县
 * @property string $address 地址
 * @property string $mobile 手机号
 * @property string $status 状态
 */
class Address extends \yii\db\ActiveRecord
{

    public function rules()
    {
        return [
         [["name","province","city","county","address","mobile"],"required"],
            ["status","safe"],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'user_id' => '用户id',
            'province' => '省',
            'city' => '市',
            'county' => '县',
            'address' => '地址',
            'mobile' => '手机号',
            'status' => '状态',
        ];
    }
}
