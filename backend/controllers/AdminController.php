<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\LoginForm;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {

        //读取所有数据
        $admins =Admin::find()->all();
        //显示视图
        return $this->render("index",compact("admins"));

    }
    public function actionLogin(){
        $login = new LoginForm();

        $request = \Yii::$app->request;

        if($request->isPost){

            //绑定数据
            $login->load($request->post());
            $result=  Admin::find()->where(["name"=>$login->name,"status"=>"1"])->one();
            //判断账号

            if($result){
                //判断密码是否正确
                if(\Yii::$app->security->validatePassword($login->password,$result->password)){

                    //登录
                    \Yii::$app->user->login($result,$login->rememberMe?0:0);

                    //跳转到首页
                    \Yii::$app->session->setFlash("success","登录成功");
                    return $this->redirect(["admin/index"]);

                }else{

                $login->addError("password","密码错误");
                }
            }else{
                $login->addError("name","账号不存在或已禁用");
            }
        }

        return $this->render("login",compact("login"));
    }

    //添加方法

    public function actionAdd()
    {
        //生成表单模型
        $model = new Admin();
        $model->setScenario("add");

        //创建一个Request对象
        $request =\Yii::$app->request;

        //判断是不是post提交

        if($request->isPost){
            //绑定数据
            $model->load($request->post());
            //给密码加密
            $model->password=\Yii::$app->security->generatePasswordHash($model->password);

            $model->auth_key=\Yii::$app->security->generateRandomString();

            $model->last_time=time();
            $model->ip=ip2long(\Yii::$app->request->userIP);

            //后端验证
            if($model->validate()){

                //保存数据
                if($model->save(false)){

                    \Yii::$app->session->setFlash("success","添加成功");
                    return $this->redirect(["admin/index"]);
                }
            }else{
                //打印错误的信息

                var_dump($model->errors);exit;
            }
        }

        //引入视图
        return $this->render("add",compact("model"));
    }



    //修改方法

    public function actionEdit($id)
    {
        $model =Admin::findOne($id);

          $model->setScenario("edit");
        $password = $model->password;
        //创建一个Request对象
        $request =\Yii::$app->request;

        //判断是不是post提交

        if($request->isPost){
            //绑定数据
            $model->load($request->post());
            if($model->password){
                //给密码加密
                $model->password=\Yii::$app->security->generatePasswordHash($model->password);
            }else{
                $model->password=$password;
            }

            //后端验证
            if($model->validate()){

                //保存数据
                if($model->save(false)){

                    \Yii::$app->session->setFlash("success","修改成功");
                    return $this->redirect(["admin/index"]);
                }
            }else{
                //打印错误的信息

                var_dump($model->errors);exit;
            }
        }

        //引入视图
        return $this->render("add",compact("model"));
    }

    //删除方法
    public function actionDel($id){
        if(Admin::findOne($id)->delete()){
            \Yii::$app->session->setFlash("success","删除成功");
            return $this->redirect(["admin/index"]);
        }
    }
    //退出方法
    public function actionOut(){
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash("success","退出成功");
        return $this->redirect(["admin/login"]);
    }
}
