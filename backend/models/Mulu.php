<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mulu".
 *
 * @property int $id
 * @property string $name
 * @property string $ico
 * @property string $url
 * @property int $parent_id 父类id
 */
class Mulu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mulu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name', 'ico', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'ico' => 'Ico',
            'url' => 'Url',
            'parent_id' => '父类id',
        ];
    }
    public static function menw(){

        $menu=[
            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],


            [
                'label' => '商品管理',
                'icon' => 'share',
                'url' => '#',
                'items' => [
                    ['label' => '商品首页', 'icon' => 'file-code-o', 'url' => ['goods/index'],],
                    ['label' => '商品添加', 'icon' => 'file-code-o', 'url' => ['goods/add'],],
                ],
            ],

        ];

        //定义一个空数组装菜单
       $menu =[];
        //得到一级目录
       $mens=self::find()->where(["parent_id"=>0])->all();

        foreach ($mens as $men){
            $newmen =[];
            $newmen["label"]=$men->name;
            $newmen["ico"]=$men->ico;
            $newmen["url"]=$men->url;


            $mens2 =self::find()->where(["parent_id"=>$men->id])->all();

            //得到二级目录
            foreach ($mens2 as $menson){
                $newmenson=[];
                $newmenson["label"]=$menson->name;
                $newmenson["ico"]=$menson->ico;
                $newmenson["url"]=$menson->url;

                $newmen["items"][]=$newmenson;
            }
            $menu[]=$newmen;

        }
        return $menu;
    }
}
