<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\22 0022
 * Time: 14:18
 */

namespace backend\models;


use yii\base\Model;

class LoginForm extends  Model
{
    public $name;
    public $password;
    public $rememberMe = true;


    public function rules()
    {
        return [
            [["name","password"],"required"],
            ["rememberMe","safe"]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '用户名',
            'password' => '密码',
        ];
    }
}