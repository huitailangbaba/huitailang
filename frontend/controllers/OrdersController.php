<?php

namespace frontend\controllers;

class OrdersController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest){
            //没有登录，强制登录
            return $this->redirect(["user/login","url"=>"/orders/index"]);
        }else{
            return $this->render('index');
        }

    }

}
