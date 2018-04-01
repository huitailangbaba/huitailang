<?php

namespace frontend\controllers;

use backend\models\Category;
use backend\models\Goods;

class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @param $id
     * @return string
     *
     * 商品列表模块
     */
    public function actionList($id){

        //通过分类id找到对象
        $cate = Category::findOne($id);

        //通过id找到所有的子孙分类
        $cateSon =Category::find()->where(["tree"=>$cate->tree])->andWhere([">=","lft",$cate->lft])->andWhere(["<=","rgt",$cate->rgt])->all();

        //取出其中的id
        $cateId = array_column($cateSon,"id");

       //得到所有的分类列表
        $goods = Goods::find()->where(["in","category_id",$cateId])->all();


        return $this->render("list",compact("goods"));

    }

/**
 * 商品详情模块
 */

      public function actionGoods($id){


          //通过id找到对应对象
          $good = Goods::findOne($id);
          return $this->render("goods",compact("good"));
      }

}
