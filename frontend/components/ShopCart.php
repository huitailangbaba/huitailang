<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\31 0031
 * Time: 15:19
 */

namespace frontend\components;


use frontend\models\Cart;
use yii\base\Component;
use yii\web\Cookie;

class ShopCart extends Component
{
     private $cart;

     //创建时是自动调用
    public function __construct(array $config = [])
    {
        $getCookie = \Yii::$app->request->cookies;
        $this->cart = $getCookie->getValue("cart",[]);
        parent::__construct($config);
    }
    //增加
    public function add($id,$num){

        //判断当前的商品是否存在
        if(array_key_exists($id,$this->cart)){
            //存在执行价的操作
            $this->cart[$id]+=$num;
        }else{
            //不存在执行添加操作
            $this->cart[$id]=(int)$num;
        }
        return $this;
    }
    //删除
    public function del($id){
        unset($this->cart[$id]);
        return $this;
    }

    //改
    public function update($id,$num){
        if ($this->cart[$id]){
            $this->cart[$id] = $num;
        }
        return $this;
    }
    //数据同步
    public function dbSyn()
    {

        $userId = \Yii::$app->user->id;

        foreach ($this->cart as $goodId => $num) {

            $cartDb = Cart::findOne(["goods_id" => $goodId, "user_id" => $userId]);

            if ($cartDb) {
                //执行加操作
                $cartDb->num += $num;
            } else {

                //不存在
                $cartDb = new Cart();
                $cartDb->user_id = $userId;
                $cartDb->num = $num;
                $cartDb->goods_id = $goodId;
            }
            //保存
            $cartDb->save();
        }
        return $this;
    }
    //保存
    public function save(){
        //创建设置cookie对象
        $setCookie =\Yii::$app->response->cookies;

        $cookie = new Cookie([
            "name"=>"cart",
            "value" => $this->cart
        ]);
        //添加一个对象
        $setCookie->add($cookie);
    }
    //查
    public function get(){
        return $this->cart;
    }
    //清除cookie的数据
    public function flush(){
        $this->cart=[];
        return $this;
    }
}