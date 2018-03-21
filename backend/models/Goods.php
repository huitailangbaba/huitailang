<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $sn 货号
 * @property string $logo 商品logo
 * @property int $category_id 商品分类
 * @property int $brand_id 品牌
 * @property string $market_price 市场价格
 * @property string $shop_price 本店价格
 * @property int $stock 库存
 * @property int $status 是否上架
 * @property int $create_time 录入时间
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $images;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                   ActiveRecord::EVENT_BEFORE_INSERT => ['create_time',],
                ],
            ],
        ];
    }

    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public static $is = ["1"=>"上线","2"=>"下线"];
    public function rules()
    {
        return [
            [["name"],"required"],
            [['category_id', 'brand_id', 'stock', 'create_time',"sort"], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [["status","logo","sn","images"],"safe"]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'sn' => '货号',
            'logo' => '商品logo',
            'category_id' => '商品分类',
            'brand_id' => '品牌',
            'market_price' => '市场价格',
            'shop_price' => '本店价格',
            "sort"=>"排序",
            'stock' => '库存',
            'status' => '是否上架',
            'create_time' => '录入时间',
        ];
    }
    public function getBrand(){
        return $this ->hasOne(Brand::className(),["id"=>"brand_id"]);
    }
   public function  getCate(){
       return $this->hasOne(Category::className(),["id"=>"category_id"]);
  }
}
