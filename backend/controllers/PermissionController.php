<?php

namespace backend\controllers;

use backend\models\AuthItem;

class PermissionController extends \yii\web\Controller
{

    /**
     * 权限列表
     */
    public function actionIndex()
    {
        //创建对象
        $auth = \Yii::$app->authManager;

        $pers = $auth->getPermissions();

        return $this->render('index',compact("pers"));

    }

    /**
     * 添加模块
     */
    public function actionAdd(){
        //创建模型
        $model = new AuthItem();

if($model->load(\Yii::$app->request->post()) && $model->validate()){

    //创建对象
    $auth = \Yii::$app->authManager;
    //创建权限
    $per=  $auth->createPermission($model->name);

    //设置描述
    $per->description=$model->description;
    //保存
    if ($auth->add($per)) {
        \Yii::$app->session->setFlash("success","权限".$model->name."添加成功");
       return $this->refresh();
    }


}
        return $this->render("add",compact("model"));
    }
    /**
     * 修改模块
     */
    public function actionEdit($name){
        //创建模型
        $model = AuthItem::findOne($name);

        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            //创建对象
            $auth = \Yii::$app->authManager;
            //得到权限
            $per=  $auth->getPermission($model->name);

            //设置描述
            $per->description=$model->description;
            //修改
            if ($auth->update($model->name,$per)) {
                \Yii::$app->session->setFlash("success","权限".$model->name."修改成功");
                return $this->redirect(["index"]);
            }


        }
        return $this->render("edit",compact("model"));
    }
/**
 * 删除模块
  */
    public function actionDel($name){
             //创建对象
             $auth = \Yii::$app->authManager;
             //找到权限
             $per = $auth->getPermission($name);
             //删除
             if ($auth->remove($per)) {
                 \Yii::$app->session->setFlash("success","权限删除成功");
                 return $this->redirect(["index"]);
             }
         }
}
