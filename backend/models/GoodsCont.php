<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_cont".
 *
 * @property int $id
 * @property int $goods_id 商品id
 * @property string $content 商品描述
 */
class GoodsCont extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_cont';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ["content","required"]

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '商品描述',
        ];
    }
}
