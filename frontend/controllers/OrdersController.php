<?php

namespace frontend\controllers;

use backend\models\Delivery;
use backend\models\Goods;
use backend\models\Order;
use backend\models\OrderDetail;
use backend\models\PayType;
use frontend\models\Address;
use frontend\models\Cart;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class OrdersController extends \yii\web\Controller
{
    public function actionIndex()
    {


        if(\Yii::$app->user->isGuest){
            //没有登录，强制登录
            return $this->redirect(["user/login","url"=>"/orders/index"]);
        }

            //用户id
            $userId = \Yii::$app->user->id;

            //收货人地址
            $address = Address::find()->where(["user_id"=>$userId])->all();
            //配送方式
            $deliveries = Delivery::find()->all();

            //支付方式
            $payTypes = PayType::find()->all();
            //取出商品
            $carts = Cart::find()->where(["user_id"=>$userId])->asArray()->all();
            //把二维数组转换为一维的
            $carts = ArrayHelper::map($carts,"goods_id","num");
            //取出所有的键值
            $goodsId = array_keys($carts);
            //根据good_id取出商品
            $goods = Goods::find()->where(["in","id",$goodsId])->all();


            //商品总价
            $shopPrice =0;
            //商品总数
            $shopNum=0;
            foreach ($goods as $good){
                //商品总价
                $shopPrice+=$good->shop_price*$carts[$good->id];
                $shopNum+=$carts[$good->id];
            }
            //改为有二位小数
            $shopPrice=number_format($shopPrice,2);




            $request = \Yii::$app->request;
            if($request->isPost){

                $db = \Yii::$app->db;
                $transaction = $db->beginTransaction();

                try {
                    //创建订单对象
                    $order = new  Order();

                    $addressId=$request->post("address_id");
                    //取出地址
                    $address=Address::findOne(["id"=>$addressId,"user_id"=>$userId]);
                    //取出配送方式
                    $deliveryId = $request->post("delivery");
                    $delivery= Delivery::findOne($deliveryId);

                    // var_dump($delivery->name);exit;
                    //取出配送id
                    $payTypeId=$request->post("pay");
                    $payType=PayType::findOne($payTypeId);
                    //var_dump($delivery);exit;

                    //给order赋值
                    $order->user_id=$userId;
                    $order->name=$address->name;
                    $order->province=$address->province;
                    $order->city=$address->city;
                    $order->area=$address->county;
                    $order->detail_address=$address->address;
                    $order->tel=$address->mobile;


                    $order->delivery_id=$deliveryId;
                    $order->delivery_price=$delivery->price;
                    $order->delivery_name=$delivery->name;

                    $order->payment_id=$payTypeId;
                    $order->payment_name=$payType->name;



                    $order->price=$shopPrice+$delivery->price;
                    $order->status=1;
                    $order->trade_no=date("ymd").rand(1000,9999);
                    $order->create_time=time();
                    if ($order->save()) {


//循环商品
                        foreach ($goods as $good){
//找到当前商品
                            $curGood=Goods::findOne($good->id);
                            if($carts[$good->id]>$curGood->stock){
                                //抛出异常
                                throw new Exception("库存不足");
                            }
                            $orderDetal = new OrderDetail();
                            $orderDetal->order_id=$order->id;

                            $orderDetal->goods_id=$good->id;

                            $orderDetal->amount=$carts[$good->id];

                            $orderDetal->goods_name=$good->name;
                            $orderDetal->logo=$good->logo;
                            $orderDetal->price=$good->shop_price;
                            $orderDetal->total_price=$good->shop_price*$orderDetal->amount;


                            //保存数据
                            if ($orderDetal->save()) {
                                //把当前商品的库存减掉
                                $curGood->stock=$curGood->stock-$carts[$good->id];
                                $curGood->save(false);


                            }
                        }


                    }

                    //清空购物车
                    Cart::deleteAll(['user_id'=>$userId]);


                    $transaction->commit();

                    return Json::encode([
                        "status"=>1,
                        "msg"=>"订单提交成功"
                    ]);

                } catch(Exception $e) {

                    $transaction->rollBack();

                    return Json::encode([
                        "status"=>0,
                        "msg"=>$e->getMessage()
                    ]);

                }


















        }
        return $this->render('index',compact("shopNum","shopPrice","address","deliveries","goods","payTypes","carts"));
    }

}
