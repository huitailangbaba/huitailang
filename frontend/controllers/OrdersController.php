<?php

namespace frontend\controllers;

use backend\models\Delivery;
use backend\models\Goods;
use backend\models\Order;
use backend\models\OrderDetail;
use backend\models\PayType;
use frontend\models\Address;
use frontend\models\Cart;
use function Sodium\compare;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use EasyWeChat\Foundation\Application;
use Endroid\QrCode\QrCode;
use yii\helpers\Url;

class OrdersController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;
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
                        "msg"=>"订单提交成功",
                        "id"=>$order->id,
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
    public function actionWx($id){



        $order = Order::findOne($id);

        $options = \Yii::$app->params["wx"];


        $app = new Application($options);

        $payment = $app->payment;


        $attributes = [
            'trade_type'       => 'NATIVE', // JSAPI，NATIVE，APP...
            'body'             => 'iPad mini 16G 白色',
            'detail'           => 'iPad mini 16G 白色',
            'out_trade_no'     => $order->trade_no,
            'total_fee'        => $order->price*100, // 单位：分
            'notify_url'       => Url::to(['orders/notify'],true), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
           // 'openid'           => '当前用户的 openid', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];

        $order = new \EasyWeChat\Payment\Order($attributes);


        $result = $payment->prepare($order);

        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
           // $prepayId = $result->prepay_id;


            $qrCode = new QrCode($result->code_url);

            header('Content-Type: '.$qrCode->getContentType());
            echo $qrCode->writeString();
        }
    }
    public function actionOk($id){
        $order = Order::findOne($id);
        return $this->render("ok",compact("order"));
    }
    public function actionNotify(){

        $options = \Yii::$app->params["wx"];


        $app = new Application($options);

        $response = $app->payment->handleNotify(function($notify, $successful){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order=Order::findOne(["trade_no"=>$notify->out_trade_no]);

            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->status!=1) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }

            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                $order->status =2;
            } else { // 用户支付失败
                $order->status = 'paid_fail';
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        return $response;
    }

}
