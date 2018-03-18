<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\18 0018
 * Time: 16:21
 */

namespace backend\compentos;


use creocoder\nestedsets\NestedSetsBehavior;

class MenuQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsBehavior::className(),
        ];
    }
}