<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\AuthAssignment;
use yii\helpers\ArrayHelper;

class AuthAssController extends \yii\web\Controller
{
    public function actionIndex()
    {
        echo "没有首页，自己去做";
    }

    public function actionAdd(){

        //创建模型
        $model= new AuthAssignment();

        //创建对象
        $auth = \Yii::$app->authManager;
        //获取所有角色
        $admins =Admin::find()->all();
        //转换为以为数组
        $admin=ArrayHelper::map($admins,"id","name");
        //获取所有的角色
        $roles = $auth->getRoles();
        $role=ArrayHelper::map($roles,"name","name");
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
         //通过角色找到角色对象
          $role=$auth->getRole($model->item_name);
         //找用户这牌的角色
            $auth->assign($role,$model->user_id);
            \Yii::$app->session->setFlash("success","管理员权限角色添加成功");
            return $this->redirect(["admin/index"]);
        }


        return $this->render("add",compact("model","admin","role"));
    }
}
