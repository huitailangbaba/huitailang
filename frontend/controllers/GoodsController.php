<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Cart;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Cookie;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }


    /**
     * 添加购物车
     */

    public function actionCart($id,$amount){
       if(\Yii::$app->user->isGuest){
           //未登录
           //得到cookie对象
           $getCookie = \Yii::$app->request->cookies;

           //得到原来购物车数据
           $cart = $getCookie->getValue("cart",[]);


           //判断当前的商品是否存在
           if(array_key_exists($id,$cart)){
               //存在执行价的操作
               $cart[$id]+=$amount;
           }else{
               //不存在执行添加操作
               $cart[$id]=(int)$amount;
           }
             //创建设置cookie对象
           $setCookie =\Yii::$app->response->cookies;

           $cookie = new Cookie([
               "name"=>"cart",
               "value" => $cart
           ]);
           //添加一个对象
           $setCookie->add($cookie);
           //返回


       }else{
           //已经登录
           $userId = \Yii::$app->user->id;

           //查找是否存在
           $cart =Cart::findOne(["goods_id"=>$id,"user_id"=>$userId]);

           if ($cart) {
               //执行加操作
               $cart->num+=$amount;
           }else{

               //不存在
               $cart = new Cart();
               $cart->user_id=$userId;
               $cart->num=$amount;
               $cart->goods_id=$id;
           }
           //保存
        $cart->save();


       }
        return $this->redirect(["cart-list"]);
    }
    public function actionCartList(){

        if(\Yii::$app->user->isGuest){
            //未登录 从cookie中取数据
            $cart =\Yii::$app->request->cookies->getValue("cart",[]);

            //取出$cart中的所有key值
            $goodId = array_keys($cart);

            //读取购物车的搜优商品
            $goods = Goods::find()->where(["in", "id",$goodId])->all();
        }else{
            //已经登录从数据库中读取
            //从数据库中取出数据
            $cart = Cart::find()->where(["user_id"=>\Yii::$app->user->id])->all();

            //把二维数组转换为一维
            $cart = ArrayHelper::map($cart,"goods_id","num");

            //取出所有的key值
            $goodIs = array_keys($cart);
            //通过id找到所有的商品
            $goods = Goods::find()->where(["in","id",$goodIs])->all();


        }

           return $this->render("flow1",compact("goods","cart"));
    }

    public function actionUpdate($id,$amount){

        if(\Yii::$app->user->isGuest){
            //取出数据
            $cart =\Yii::$app->request->cookies->getValue("cart",[]);
            //修改对应数据
            $cart[$id]=$amount;

            //创建设置cookie对象
            $setCookie =\Yii::$app->response->cookies;

            $cookie = new Cookie([
                "name"=>"cart",
                "value" => $cart
            ]);
            //添加一个对象
            $setCookie->add($cookie);
        }else{
            $cart = Cart::find()->where(["goods_id"=>$id,"user_id"=>\Yii::$app->user->id])->all();

        }
    }
    public function actionDel($id){

        if(\Yii::$app->user->isGuest){
            //取出数据
            $cart =\Yii::$app->request->cookies->getValue("cart",[]);
              unset($cart[$id]);


            //创建设置cookie对象
            $setCookie =\Yii::$app->response->cookies;

            $cookie = new Cookie([
                "name"=>"cart",
                "value" => $cart
            ]);
            //添加一个对象
            $setCookie->add($cookie);

            return Json::encode([
                "status"=>1,
                "msg"=>"删除成功"
            ]);
        }
    }
}
