<?php

namespace frontend\controllers;

use frontend\models\Address;
use function Sodium\compare;
use yii\helpers\Json;

class AddressController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionAddress(){

        $addArr = Address::find()->where(["user_id"=>\Yii::$app->user->id])->all();

        $request = \Yii::$app->request;


        if($request->isPost){

            $address = new Address();
            $address->load($request->post());
            if($address->validate()){

                if($address->status==null){

                    $address->status=0;
                }else{
                    $address->status=1;
                    //把其他的id改为0
                    Address::updateAll(["status"=>0,"user_id"=>\Yii::$app->user->id]);
                }
                 //插入id
                $address->user_id=\Yii::$app->user->id;
                if($address->save()){
                    return Json::encode([
                        "status"=>1,
                        "msg"=>"添加成功",
                    ]);
                }
            }else{
                return Json::encode([
                    "status"=>0,
                    "msg"=>"添加失败",
                    "data"=>$address->errors,
                ]);
            }
        }


        return $this->render("address",compact("addArr"));
    }

 public function actionDel($id){
     if (Address::findOne(["id"=>$id,"user_id"=>\Yii::$app->user->id])->delete()) {
         return $this->redirect(["address"]);
     }
 }
}
