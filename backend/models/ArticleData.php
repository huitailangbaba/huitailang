<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\17 0017
 * Time: 16:51
 */

namespace backend\models;


use yii\db\ActiveRecord;

class ArticleData extends ActiveRecord
{
    public function rules()
    {
        return [
            ["content","safe"],
            ["article_id","safe"]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
           "content"=>"内容"
        ];
    }
}