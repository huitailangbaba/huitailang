<?php

namespace backend\controllers;

use backend\models\AuthItem;
use yii\helpers\ArrayHelper;

class RoleController extends \yii\web\Controller
{

    /**
     * 角色列表
     */
    public function actionIndex()
    {
        //创建对象
        $auth = \Yii::$app->authManager;

        $roles = $auth->getRoles();

        return $this->render('index',compact("roles"));

    }

    /**
     * 添加模块
     */
    public function actionAdd(){
        //创建模型
        $model = new AuthItem();
        //创建对象
        $auth = \Yii::$app->authManager;
        //得到所有权限
       $pers= $auth->getPermissions();
       $perArr = ArrayHelper::map($pers,"name","description");

if($model->load(\Yii::$app->request->post()) && $model->validate()){


    //创建权限
    $role=  $auth->createRole($model->name);

    //设置描述
    $role->description=$model->description;
    //保存
    if ($auth->add($role)) {
        //判断有没有添加权限
        if($model->perms){
            //循环权限
            foreach ($model->perms as $pesName){
                //通过权限得到权限杜对象
                $pes=$auth->getPermission($pesName);
                //给角色添加权限
                $auth->addChild($role,$pes);

            }
        }



        \Yii::$app->session->setFlash("success","角色".$model->name."添加成功");
       return $this->refresh();
    }


}
        return $this->render("add",compact("model","perArr"));
    }
    /**
     * 修改模块
     */

    public function actionEdit($name){
        //创建模型
        $model = AuthItem::findOne($name);
        //创建对象
        $auth = \Yii::$app->authManager;
        //得到所有权限
        $pers= $auth->getPermissions();
        $perArr = ArrayHelper::map($pers,"name","description");
        //得到当前角色的所有权限
        $roll=$auth->getPermissionsByRole($name);

        $model->perms=array_keys($roll);

        if($model->load(\Yii::$app->request->post()) && $model->validate()){


            //创建权限
            $role=  $auth->getRole($model->name);

            //设置描述
            $role->description=$model->description;
            //跟新角色
            if ($auth->update($model->name,$role)) {

                //删除所有权限
                $auth->removeChildren($role);

                //判断有没有添加权限
                if($model->perms){
                    //循环权限
                    foreach ($model->perms as $pesName){
                        //通过权限得到权限杜对象
                        $pes=$auth->getPermission($pesName);
                        //给角色添加权限
                        $auth->addChild($role,$pes);

                    }
                }



                \Yii::$app->session->setFlash("success","角色".$model->name."修改成功");
                return $this->redirect(["index"]);
            }


        }


        return $this->render("edit",compact("model","perArr"));
    }
/**
 * 删除模块
  */
    public function actionDel($name){
             //创建对象
             $auth = \Yii::$app->authManager;
             //找到权限
             $role = $auth->getRole($name);
             //删除
             if ($auth->remove($role)) {
                 \Yii::$app->session->setFlash("success","角色删除成功");
                 return $this->redirect(["index"]);
             }
         }
}
