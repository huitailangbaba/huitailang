<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_path".
 *
 * @property int $id
 * @property int $goods_id
 * @property string $path 商品图片的地址
 */
class GoodsPath extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_path';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Goods ID',
            'path' => '商品图片的地址',
        ];
    }
}
