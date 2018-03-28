<?php

namespace frontend\controllers;

use frontend\models\User;
use Mrgoon\AliSms\AliSms;
use yii\helpers\Json;

class UserController extends \yii\web\Controller
{
    public function actions()
    {
        return [
            'code' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                "maxLength" => "3",
                "minLength" => "3",
            ],
        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 注册模块
     */
    public function actionReg(){

        $request = \Yii::$app->request;

        //判断是否是post
        if($request->isPost){
            $user = new User();
            //设置场景
            $user->setScenario(User::SCENARIO_LOGIN);
            $user->load($request->post());

            if($user->validate()){
                   //令牌
                $user->auth_key=\Yii::$app->security->generateRandomString();
                 //密码加密
                $user->password_hash=\Yii::$app->security->generatePasswordHash($user->password);

                $user->login_time=time();
                $user->login_ip=ip2long(\Yii::$app->request->userIP);

                //保存数据
                if ($user->save(false)) {

                    $result=[
                        "status"=>1,
                        "msg"=>"注册成功",
                        "data"=>"",

                    ];
                    return Json::encode($result);
                }
            }else{
                $result=[
                    "status"=>0,
                    "msg"=>"注册失败",
                    "data"=>$user->errors,
                ];
                return Json::encode($result);
            }


        }

        return $this->render("reg");
    }


    public function actionSendSms($mobile){

        //1生成验证码
        $code = rand(100000,999999);
        //2

        $config = [
            'access_key' => 'LTAIS1zP1jC0kDZb',
            'access_secret' => 'xCj27J3EJ9mxqFPWLiwaHrZ1co1Voz',
            'sign_name' => '唐宇',
        ];

        $aliSms = new AliSms();
        $response = $aliSms->sendSms($mobile, 'SMS_128636096', ['code'=> $code], $config);
        if($response->Message=="OK"){
            //3  把验证码保存到session中去

            $session = \Yii::$app->session;

            $session->set("tel_".$mobile,$code);
            //测试
            return $code;
        }else{
            var_dump($response->Message);
        }



    }
    /**
     * 登录模块
     */

    public function actionLogin(){
        $request = \Yii::$app->request;

        if($request->isPost){
            $model = new User();
            //设置场景
            $model->setScenario(User::SCENARIO_REGISTER);
            //绑定数据
            $model->load($request->post());
            if($model->validate()){
                //判断账号
                $user =User::findOne(["username"=>$model->username]);


                if($user && \Yii::$app->security->validatePassword($model->password,$user->password_hash)) {

                    //登录
                    \Yii::$app->user->login($user,$model->rememberMe?3600*24*7:0);


                    $result =[
                        "status"=>1,
                        "msg"=>"登录成功",
                        "data"=>"",
                    ];

                    return Json::encode($result);

                }else{


                    //密码或者用户名错误
                    $result =[
                        "status"=>0,
                        "msg"=>"用户名或者密码有误",
                        "data"=>null,
                    ];

                    return Json::encode($result);


                }

            }else{
                //输入的有错误
               $result =[
                   "status"=>0,
                   "msg"=>"输入有误",
                   "data"=>$model->errors,
               ];

               return Json::encode($result);
            }


        }

        return $this->render("login");
    }
    public function actionLoginout(){
        \Yii::$app->user->logout();

    }

}
